<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use UAH\GestorActividadesBundle\Entity\Enrollment;
use UAH\GestorActividadesBundle\Entity\Application;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApplicationController extends Controller {

    //Success constants
    const APPLICATION_SUCCESS_CREATED = 0;
    const APPLICATION_SUCCESS_DELETED = 5;
    const APPLICATION_SUCCESS_ARCHIVED = 7;
    //Error constants
    const APPLICATION_ERROR_NOT_YOUR_OWN = 1;
    const APPLICATION_ERROR_NOT_RECOGNIZED = 2;
    const APPLICATION_ERROR_CSRF_TOKEN_INVALID = 3;
    const APPLICATION_ERROR_NOT_DELETED = 4;
    const APPLICATION_ERROR_ALREADY_USED = 6;
    const APPLICATION_ERROR_NOT_ARCHIVED = 8;

    /**
     * @Route("/applications")
     * 
     */
    public function indexAction() {
        $applications = $this->getUser()->getApplications();

        $response = $this->render('UAHGestorActividadesBundle:Application:index.html.twig', array(
            'applications' => $applications));
        $token = $this->get('form.csrf_provider')->generateCsrfToken('application');
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $response->headers->setCookie($cookie);
        return $response;
    }

    /**
     * @Route("/applications/show/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1}, options={"expose"=true})
     * @Route("/applications/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1}), options={"expose"=true})
     * @ParamConverter("application", class="UAHGestorActividadesBundle:Application", options={"id" = "application_id"})
     */
    public function showAction(Application $application) {
        $enrollments = $application->getEnrollments();
        $typeOfCredits = $enrollments[0]->getCreditsType();
        return $this->render('UAHGestorActividadesBundle:Application:show.html.twig', array(
                    'application' => $application,
                    'enrollments' => $enrollments,
                    'typeOfCredits' => $typeOfCredits
        ));
    }

    /**
     * 
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @Route("/application/create/",options={"expose"=true})
     * @Security("has_role('ROLE_UAH_STUDENT')")
     */
    public function createAction(Request $request) {
        //Comprobación CSRF
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('profile', $request->headers->get('X-CSRFToken'))) {

            $enrollments = json_decode($request->getContent());
            $em = $this->getDoctrine()->getManager();
            $enrollment_repository = $em->getRepository('UAHGestorActividadesBundle:Enrollment');
            $status_recognized = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getRecognizedStatus();
            $respuesta_json = array();
            $application = new Application();
            $valid_enrollment = true;
            $status_pending_verification = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')
                    ->getPendingVerificationStatus();
            foreach ($enrollments as $enrollment) {
                $enrollment = $enrollment_repository->find($enrollment);
                //Compruebo el estado de la inscripcion y que la inscripción se corresponda con una inscripción del usuario
                //Si no paso esta comprobación de seguridad, guardo un error y continuo con otro
                if ($enrollment->getStatus() !== $status_recognized) {
                    $respuesta_json['type'] = 'error';
                    $respuesta_json['code'] = self::APPLICATION_ERROR_NOT_RECOGNIZED;
                    $respuesta_json['message'] = 'El estado no es el correcto';
                    $valid_enrollment = false;
                    break;
                }
                //Compruebo que el usuario es el del enrollment
                if ($enrollment->getUser() != $this->getUser()) {
                    $respuesta_json['type'] = 'error';
                    $respuesta_json['code'] = self::APPLICATION_ERROR_NOT_YOUR_OWN;
                    $respuesta_json['message'] = 'No es el tuyo';
                    $valid_enrollment = false;
                    break;
                }
                if (false === is_null($enrollment->getApplication())) {
                    $respuesta_json['type'] = 'error';
                    $respuesta_json['code'] = self::APPLICATION_ERROR_ALREADY_USED;
                    $respuesta_json['message'] = 'Alguna de las inscripciones esta ya en otro justificante';
                    $valid_enrollment = false;
                    break;
                }
                $enrollment->setApplication($application); //addEnrollment($enrollment);
                $enrollment->setStatus($status_pending_verification);
                $em->persist($enrollment);
            }

            if ($valid_enrollment) {
                $application->setStatus($em->getRepository('UAHGestorActividadesBundle:Statusapplication')->getDefault());
                $application->setApplicationDateCreated(new \DateTime(date("c", time())));
                $sr = new SecureRandom();
                $code = bin2hex($sr->nextBytes(10));
                $application->setVerificationCode($code);
                $application->setUser($this->getUser());

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
     * @Route("/application/delete/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1}, options={"expose"=true}))
     * @ParamConverter("application", class="UAHGestorActividadesBundle:Application", options={"id" = "application_id"})
     */
    public function deleteAction(Application $application, Request $request) {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('application', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();

            $status_pending_verification = $em->getRepository('UAHGestorActividadesBundle:Statusapplication')
                    ->getDefault();
            $status_recognized = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getRecognizedStatus();
            $response = array();
            $valid_data = true;
            if ($application->getStatus() === $status_pending_verification) {
                foreach ($application->getEnrollments() as $enrollment) {
                    if ($enrollment->getUser() === $this->getUser()) {
                        $enrollment->setStatus($status_recognized);
                        $enrollment->setApplication(null);
                        $em->persist($enrollment);
                    } else {
                        $valid_data = false;
                        break;
                    }
                }
                if ($valid_data) {
                    $em->remove($application);
                    $em->flush();
                    $response['code'] = self::APPLICATION_SUCCESS_DELETED;
                    $response['message'] = 'Justificante borrado!';
                    $response['type'] = 'success';
                } else {
                    $response['code'] = self::APPLICATION_ERROR_NOT_DELETED;
                    $response['message'] = 'Justificante NO borrado!';
                    $response['type'] = 'error';
                }
            } else {
                $response['code'] = self::APPLICATION_ERROR_NOT_DELETED;
                $response['message'] = 'Justificante NO borrado!';
                $response['type'] = 'error';
            }
            if ($response['type'] === 'success') {
                $response_code = 200;
            } else {
                $response_code = 400;
            }
            return new JSONResponse($response, $response_code);
        }
    }

    /**
     * @Route("/application/archive/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1}, options={"expose"=true}))
     * @ParamConverter("application", class="UAHGestorActividadesBundle:Application",options={"id" = "application_id"})
     */
    public function archiveAction(Application $application, Request $request) {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('application', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();

            $status_archived = $em->getRepository('UAHGestorActividadesBundle:Statusapplication')
                    ->getArchived();
            $status_verified = $em->getRepository('UAHGestorActividadesBundle:Statusapplication')->getVerified();
            $response = array();

            if ($application->getStatus() === $status_verified) {
                $enrollment = $application->getEnrollments();
                $enrollment = $enrollment[0];
                if ($enrollment->getUser() === $this->getUser()) {
                    $application->setStatus($status_archived);
                    $em->persist($application);
                    $em->flush();
                    $response['code'] = self::APPLICATION_SUCCESS_ARCHIVED;
                    $response['message'] = 'Justificante archivado!';
                    $response['type'] = 'success';
                } else {
                    $response['code'] = self::APPLICATION_ERROR_NOT_ARCHIVED;
                    $response['message'] = 'Justificante NO archivado!';
                    $response['type'] = 'error';
                }
            } else {
                $response['code'] = self::APPLICATION_ERROR_NOT_ARCHIVED;
                $response['message'] = 'Justificante NO archivado. <br>Pendiente de verificación!';
                $response['type'] = 'error';
            }
            if ($response['type'] === 'success') {
                $response_code = 200;
            } else {
                $response_code = 400;
            }
            return new JSONResponse($response, $response_code);
        }
    }

}
