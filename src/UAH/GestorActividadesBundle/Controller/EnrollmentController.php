<?php

namespace UAH\GestorActividadesBundle\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use UAH\GestorActividadesBundle\Entity\Enrollment;
use UAH\GestorActividadesBundle\Entity\Activity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UAH\GestorActividadesBundle\Repository\EnrollmentRepository;
use Symfony\Component\HttpFoundation\Cookie;
use NumberFormatter;

class EnrollmentController extends Controller {

    const ENROLLMENT_OK = 0;
    //Error cuando ya estoy inscrito en la actividad
    const ENROLLMENT_ERROR_ALREADY_ENROLLED = 1;
    //Error cuando no me puedo inscribir en la actividad por que no hay más plazas
    const ENROLLMENT_ERROR_NO_PLACES = 2;
    //Error cuando no me puedo inscribir en la actividad por su estado
    const ENROLLMENT_ERROR_INVALID_ACTIVITY = 4;
    const ENROLLMENT_ERROR_UNKNOWN = 8;
    const RECOGNIZEMENT_ERROR_CSRF_TOKEN_INVALID = 1;
    const RECOGNIZEMENT_ERROR_BASIC = 2;
    const RECOGNIZEMENT_ERROR_NO_DEGREE = 3;

    /**
     * @Route("/enroll/{activity_id}",requirements={"pagina" = "\d+"}, options={"expose"=true})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("is_granted('ROLE_UAH_STUDENT')")
     */
    public function enrollAction(Activity $activity, Request $request) {
        /*
         * 
         * Inscribo al usuario
         */

        //Cargo el usuario
        $user = $this->getUser();
        //Compruebo que la actividad tiene huecos libres (places_occupied<places_offered)
        //Compruebo que el usuario no esta ya inscrito en la actividad (Si ya esta, le devuelvo ok)
        $em = $this->getDoctrine()->getManager();
        $valido = $this->get('form.csrf_provider')->isCsrfTokenValid('basico', $request->headers->get('X-CSRFToken'));

        //Uso bitmasks para saber que tipo de error hay (si lo hay) 
        $check_enrolled = $em->getRepository('UAHGestorActividadesBundle:Enrollment')->checkEnrolled($user, $activity);
        //Si el numero de plazas ofertadas es null siempre puedo inscribirme por esto
        $free_places = (is_null($activity->getNumberOfPlacesOffered()) ||  
                $activity->getNumberOfPlacesOccupied() >= $activity->getNumberOfPlacesOffered()) << 1;
        $can_enroll = !($em->getRepository('UAHGestorActividadesBundle:Enrollment')->canEnroll($activity)) << 2;
        $permissions = 0;
        $permissions |= $check_enrolled;
        $permissions |= $free_places;
        $permissions |= $can_enroll;
        //$permissions = $check_enrolled | $free_places << 1 | $can_enroll << 2;
        $response = array();

        if ($permissions & self::ENROLLMENT_ERROR_ALREADY_ENROLLED) {
            $response['type'] = 'notice';
            $response['code'] = self::ENROLLMENT_ERROR_ALREADY_ENROLLED;
            $response['message'] = 'Ya estás inscrito!';
            $code = 403;
        } else if ($permissions & self::ENROLLMENT_ERROR_NO_PLACES) {
            $response['type'] = 'notice';
            $response['code'] = self::ENROLLMENT_ERROR_NO_PLACES;
            $response['message'] = 'No hay plazas libres.';
            $code = 403;
        } else if ($permissions & self::ENROLLMENT_ERROR_INVALID_ACTIVITY) {
            $response['type'] = 'error';
            $response['code'] = self::ENROLLMENT_ERROR_INVALID_ACTIVITY;
            $response['message'] = 'Actividad no disponible.';
            $code = 403;
        } else {
            $enrollment = new Enrollment();
            $enrollment->setActivity($activity);
            $enrollment->setUser($user);
            $em->persist($enrollment);
            $activity->setNumberOfPlacesOccupied($activity->getNumberOfPlacesOccupied() + 1);
            $em->persist($activity);
            $em->flush();
            $code = 200;
            $response = 'Enrolled';
        }
        return new JsonResponse($response, $code);
    }

    /**
     * 
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @param \Symfony\Component\HttpFoundation\Request $request
     * 
     * @Route("/enroll/recognize/{activity_id}",requirements={"activity_id" = "\d+"}, options={"expose"=true})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && has_role('ROLE_UAH_STAFF_PDI')) || has_role('ROLE_UAH_ADMIN')")
     */
    public function recognizeAction(Activity $activity, Request $request) {
        //Comprobación CSRF
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('administracion', $request->headers->get('X-CSRFToken'))) {
            $recognizements = json_decode($request->getContent());
            $em = $this->getDoctrine()->getManager();
            $enrollment_repository = $em->getRepository('UAHGestorActividadesBundle:Enrollment');
            $status_recognized = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getRecognizedStatus();
            $respuesta_json = array();
            foreach ($recognizements as $recognizement) {
                $respuesta = array();
                $enrollment = $enrollment_repository->find($recognizement->id);
                //Compruebo el estado de la inscripcion y que la inscripción se corresponda con mi actividad
                //Si no paso esta comprobación de seguridad, guardo un error y continuo
                if (!($enrollment->getStatus()->getCode() === "STATUS_ENROLLED") ||
                        !($enrollment->getActivity()->getId() === $activity->getId())) {
                    $respuesta['type'] = 'error';
                    $respuesta['code'] = self::RECOGNIZEMENT_ERROR_BASIC;
                    $respuesta['message'] = 'El estado no es el correcto';
                    $respuesta_json[$recognizement->id] = $respuesta;
                    continue;
                }
                //Compruebo el estado del grado. Grados no activos no pueden calcular sus créditos
                if (!($enrollment->getUser()->getDegreeId()->getStatus()->getCode() == "STATUS_RENEWED") &&
                        !($enrollment->getUser()->getDegreeId()->getStatus()->getCode() == "STATUS_NON_RENEWED")) {
                    $respuesta['type'] = 'error';
                    $respuesta['code'] = self::RECOGNIZEMENT_ERROR_NO_DEGREE;
                    $respuesta['message'] = 'No tiene un plan de estudios valido';
                    $respuesta_json[$recognizement->id] = $respuesta;
                    continue;
                }

                $num_formatter = new \NumberFormatter('es_ES', NumberFormatter::DECIMAL);
                $num_credits = $num_formatter->parse($recognizement->number_of_credits);

                if (!$num_credits) {
                    $respuesta['type'] = 'error';
                    $respuesta['code'] = self::RECOGNIZEMENT_ERROR_WRONG_NUMBER_FORMAT;
                    $respuesta['message'] = 'Formato de número incorrecto';
                    $respuesta_json[$recognizement->id] = $respuesta;
                    continue;
                }
                if ($enrollment->getUser()->getDegreeId()->getStatus()->getCode() == "STATUS_RENEWED") {
                    $num_credits_min = $activity->getNumberOfECTSCreditsMin();
                    $num_credits_max = $activity->getNumberOfECTSCreditsMax();
                } else {
                    $num_credits_min = $activity->getNumberOfCreditsMin();
                    $num_credits_max = $activity->getNumberOfCreditsMax();
                }

                //Check del número de créditos se corresponde con los rangos válidos
                if ($num_credits >= $num_credits_min &&
                        $num_credits <= $num_credits_max) {
                    //Pasa todos los checks, actualizo el registro enrollment con los valores correspondientes
                    $enrollment->setRecognizedCredits($num_credits);

                    $enrollment->setStatus($status_recognized);

                    $em->persist($enrollment);
                    $valid_input = true;
                } else {
                    $respuesta['type'] = 'error';
                    $respuesta['code'] = self::RECOGNIZEMENT_ERROR_WRONG_NUMBER;
                    $respuesta['message'] = 'Número de créditos fuera de rango';
                    $respuesta_json[$recognizement->id] = $respuesta;
                }
            }
            if (isset($valid_input) and $valid_input) {
                $em->flush();
            }
            return new JsonResponse($respuesta_json, 200);
        } else {
            $response = array();
            $response['code'] = self::RECOGNIZEMENT_ERROR_CSRF_TOKEN_INVALID;
            $response['message'] = 'El token CSRF no es válido. Intentalo de nuevo';
            $response['type'] = 'error';
            $json_response = new JsonResponse($response, 403);
            $cookie = new Cookie('X-CSRFToken', $this->get('form.csrf_provider')->generateCsrfToken('administracion'), 0, '/', null, false, false);
            $json_response->headers->setCookie($cookie);
            return $json_response;
        }
    }

    /**
     * 
     * @param \UAH\GestorActividadesBundle\Entity\Enrollment $enrollment
     * @param \Symfony\Component\HttpFoundation\Request $request
     * 
     * @Route("/enroll/unrecognize/{activity_id}",requirements={"activity_id" = "\d+"}, options={"expose"=true})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && has_role('ROLE_UAH_STAFF_PDI')) || has_role('ROLE_UAH_ADMIN')")
     */
    public function unrecognizeAction(Activity $activity, Request $request) {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('administracion', $request->headers->get('X-CSRFToken'))) {
            $id_unrecognizements = json_decode($request->getContent());
            $em = $this->getDoctrine()->getManager();
            $enrollments = $em->getRepository('UAHGestorActividadesBundle:Enrollment')->getEnrollmentsByID($id_unrecognizements);
            foreach ($enrollments as $enrollment) {
                if ($enrollment->getActivity() === $activity) {
                    $enrollment->setRecognizedCredits(NULL);
                    $enrollment->setStatus($em->getRepository('UAHGestorActividadesBundle:Statusenrollment')
                                    ->getDefault());
                    $em->persist($enrollment);
                }
            }
            $em->flush();
            return new JsonResponse("OK", 200);
        } else {
            $response = array();
            $response['code'] = self::RECOGNIZEMENT_ERROR_CSRF_TOKEN_INVALID;
            $response['message'] = 'El token CSRF no es válido. Recarga la página e inténtalo de nuevo';
            $response['type'] = 'error';
            $json_response = new JsonResponse($response, 403);
            //$cookie = new Cookie('X-CSRFToken', $this->get('form.csrf_provider')->generateCsrfToken('administracion'), 0, '/', null, false, false);
            //$json_response->headers->setCookie($cookie);
            return $json_response;
        }
    }

}
