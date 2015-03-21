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
use Symfony\Component\HttpFoundation\Cookie;
use NumberFormatter;

/**
 * @Route("/enroll")
 */
class EnrollmentController extends Controller
{

    const ENROLLMENT_OK = 0;
    //Error cuando ya estoy inscrito en la actividad
    const ENROLLMENT_ERROR_ALREADY_ENROLLED = 1;
    //Error cuando no me puedo inscribir en la actividad por que no hay más plazas
    const ENROLLMENT_ERROR_NO_PLACES = 2;
    //Error cuando no me puedo inscribir en la actividad por su estado
    const ENROLLMENT_ERROR_INVALID_ACTIVITY = 4;
    const ENROLLMENT_ERROR_NOT_FULL_PROFILE = 8;
    const ENROLLMENT_ERROR_UNKNOWN = 16;
    const RECOGNIZEMENT_ERROR_CSRF_TOKEN_INVALID = 1;
    const RECOGNIZEMENT_ERROR_BASIC = 2;
    const RECOGNIZEMENT_ERROR_NO_DEGREE = 3;
    //Tipos de creditos
    const ENROLLMENT_ECTS_CREDITS = "ECTS";
    const ENROLLMENT_LIBRE_CREDITS = "LIBRE";
    const UNENROLLMENT_FAIL = 3;
    const UNENROLLMENT_OK = 3;

    /**
     * @Route("/{activity_id}",requirements={"pagina" = "\d+"}, options={"expose"=true})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("is_granted('ROLE_UAH_STUDENT')")
     */
    public function enrollAction(Activity $activity, Request $request)
    {
        /* @var $enrollmentService \UAH\GestorActividadesBundle\Services\EnrollmentService */
        $enrollmentService = $this->get('uah.services.enrollment_service');
        try {
            $enrollmentService->createEnrollment($activity, $this->getUser());
            return new JsonResponse('Enrolled');
        } catch (Exception $ex) {
            return $this->get('uah.services.json_helper')->generateResponseFromException($ex);
        }
    }

    /**
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @param \Symfony\Component\HttpFoundation\Request    $request
     * @Route("/recognize/{activity_id}",requirements={"activity_id" = "\d+"}, options={"expose"=true})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && has_role('ROLE_UAH_STAFF_PDI')) || has_role('ROLE_UAH_ADMIN')")
     */
    public function recognizeAction(Activity $activity, Request $request)
    {
        //Comprobación CSRF
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('administracion', $request->headers->get('X-CSRFToken'))) {
            $recognizements = json_decode($request->getContent(), true);
            /* @var $enrollmentService \UAH\GestorActividadesBundle\Services\EnrollmentService */
            $enrollmentService = $this->get('uah.services.enrollment_service');
            try {
                $enrollmentService->recognizeEnrollments($recognizements, $activity, $this->getUser());
                $respuesta_json = array();
                $respuesta_json['message'] = 'Creditos reconocidos correctamente';
                return new JsonResponse($respuesta_json);
            } catch (Exception $ex) {
                return $this->get('uah.services.json_helper')->generateResponseFromException($ex);
            }
        } else {
            return $this->get('uah.services.invalid_token_response')->generateInvalidCSRFTokenResponse('profile');
        }
    }

    /**
     *
     * @param \UAH\GestorActividadesBundle\Entity\Enrollment $enrollment
     * @param \Symfony\Component\HttpFoundation\Request      $request
     *
     * @Route("/unrecognize/{activity_id}",requirements={"activity_id" = "\d+"}, options={"expose"=true})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && has_role('ROLE_UAH_STAFF_PDI')) || has_role('ROLE_UAH_ADMIN')")
     */
    public function unrecognizeAction(Activity $activity, Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('administracion', $request->headers->get('X-CSRFToken'))) {
            $id_unrecognizements = json_decode($request->getContent());
            /* @var $enrollmentService \UAH\GestorActividadesBundle\Services\EnrollmentService */
            $enrollmentService = $this->get('uah.services.enrollment_service');
            try {
                $enrollmentService->unrecognizeEnrollments($id_unrecognizements, $activity);
                return new JsonResponse("OK", 200);
            } catch (Exception $ex) {
                return $this->get('uah.services.response_from_exception')->generateResponseFromException($ex);
            }
        } else {
            return $this->get('uah.services.invalid_token_response')->generateInvalidCSRFTokenResponse('profile');
        }
    }

    /**
     *
     * @param \UAH\GestorActividadesBundle\Entity\Enrollment $enrollment
     * @param \Symfony\Component\HttpFoundation\Request      $request
     * @Route("/unenroll/{enrollment_id}", requirements={"enrollment_id" = "\d+"}, options={"expose" = true})
     * @ParamConverter("enrollment",  class="UAHGestorActividadesBundle:Enrollment",options={"id" = "enrollment_id"}))
     * @Security("enrollment.getUser() === user")
     */
    public function unenrollAction(Enrollment $enrollment, Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('profile', $request->headers->get('X-CSRFToken'))) {
            /* @var $enrollmentService \UAH\GestorActividadesBundle\Services\EnrollmentService */
            $enrollmentService = $this->get('uah.services.enrollment_service');
            try {
                $enrollmentService->removeEnrollment($enrollment);
                $response = array();
                $response['message'] = 'Te has desinscrito correctamente';
                $response['type'] = 'success';
                return new JsonResponse($response);
            } catch (\Exception $ex) {
                return $this->get('uah.services.response_from_exception')->generateResponseFromException($ex);
            }
        } else {
            return $this->get('uah.services.invalid_token_response')->generateInvalidCSRFTokenResponse('profile');
        }
    }

}
