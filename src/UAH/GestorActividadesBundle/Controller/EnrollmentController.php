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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        $free_places = ($activity->getNumberOfPlacesOccupied() >= $activity->getNumberOfPlacesOffered()) << 1;
        $can_enroll = ($em->getRepository('UAHGestorActividadesBundle:Enrollment')->canEnroll($activity));
        $permissions = $check_enrolled | $free_places << 1 | $can_enroll << 2;
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
     * @Route("/enroll/recognize/{activity_id}",requirements={"pagina" = "\d+"}, options={"expose"=true})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && has_role('ROLE_UAH_STAFF_PDI')) || has_role('ROLE_UAH_ADMIN')")
     */
    public function recognizeAction(Activity $activity, Request $request) {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('administracion', $request->headers->get('X-CSRFToken'))) {
            $content = json_decode($request->getContent());
            foreach ($content as $recognizement) {
                $id = $recognizement->id;
                $number_of_credits = $recognizement->numero_de_creditos;
            }
            return new JsonResponse(true, 200);
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

}
