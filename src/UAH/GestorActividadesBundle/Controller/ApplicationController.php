<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UAH\GestorActividadesBundle\Entity\Enrollment;
use UAH\GestorActividadesBundle\Entity\Application;

class ApplicationController extends Controller {

    const APPLICATION_SUCCESS_CREATED = 0;
    const APPLICATION_ERROR_NOT_YOUR_OWN = 1;
    const APPLICATION_ERROR_NOT_RECOGNIZED = 2;

    /**
     * @Route("/application/show/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1})
     * @Route("/application/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1})
     * @ParamConverter("application", class="UAHGestorActividadesBundle:Application",options={"id" = "application_id"})
     */
    public function showAction(Application $application) {
        
    }

    /**
     * 
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @Route("/application/create/",options={"expose"=true})
     * @Security("has_role('ROLE_UAH_STUDENT'))")
     */
    public function createAction(Request $request) {
        //Comprobación CSRF
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('application', $request->headers->get('X-CSRFToken'))) {

            $enrollments = json_decode($request->getContent());
            $em = $this->getDoctrine()->getManager();
            $enrollment_repository = $em->getRepository('UAHGestorActividadesBundle:Enrollment');
            $status_recognized = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getRecognizedStatus();
            $respuesta_json = array();
            $application = new Application();
            $valid_enrollment = true;

            foreach ($enrollments as $enrollment) {
                $enrollment = $enrollment_repository->find($enrollment->id);
                //Compruebo el estado de la inscripcion y que la inscripción se corresponda con una inscripción del usuario
                //Si no paso esta comprobación de seguridad, guardo un error y continuo con otro
                if ($enrollment->getStatus() !== $status_recognized) {

                    $respuesta_json['type'] = 'error';
                    $respuesta_json['code'] = self::APPLICATION_ERROR_NOT_RECOGNIZED;
                    $respuesta_json['message'] = 'El estado no es el correcto';
                    $respuesta_json[$enrollment->id] = $respuesta_json;
                    $valid_enrollment = false;
                    break;
                }
                //Compruebo que el usuario es el del enrollment
                if ($enrollment->getUser() != $this->getUser()) {
                    $respuesta_json['type'] = 'error';
                    $respuesta_json['code'] = self::APPLICATION_ERROR_NOT_YOUR_OWN;
                    $respuesta_json['message'] = 'No es el tuyo';
                    $respuesta_json[$enrollment->id] = $respuesta_json;
                    continue;
                }
                $application->addEnrollment($enrollment);
            }

            if (isset($valid_enrollment) and $valid_enrollment) {
                $application->setStatus($em->getRepository('UAHGestorActividadesBundle:Statusapplication')->getDefault());
                $application->setApplicationDateCreated(new \DateTime(date("c", time())));
                $em->persist($application);
                $em->flush();
                $respuesta_json['type'] = 'success';
                $respuesta_json['code'] = self::APPLICATION_SUCCESS_CREATED;
                $respuesta_json['message'] = $application->getId();
                return new JsonResponse($respuesta_json, 200);
            }
            return new JsonResponse($respuesta_json, 400);
        } else {
            $response = array();
            $response['code'] = self::APPLICATION_ERROR_CSRF_TOKEN_INVALID;
            $response['message'] = 'El token CSRF no es válido. Intentalo de nuevo';
            $response['type'] = 'error';
            $json_response = new JsonResponse($response, 403);
            $cookie = new Cookie('X-CSRFToken', $this->get('form.csrf_provider')->generateCsrfToken('application'), 0, '/', null, false, false);
            $json_response->headers->setCookie($cookie);
            return $json_response;
        }
    }

    /**
     * @Route("/application/delete/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1})
     */
    public function deleteAction(Application $application) {
        
    }

    /**
     * @Route("/application/archive/{id}")
     * @ParamConverter("application", class="UAHGestorActividadesBundle:Application",options={"id" = "application_id"})
     */
    public function archiveAction(Application $application) {
        
    }

}
