<?php

namespace UAH\GestorActividadesBundle\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UAH\GestorActividadesBundle\Entity\Enrollment;
use UAH\GestorActividadesBundle\Entity\Activity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/enroll")
 */
class EnrollmentController extends Controller
{

    /**
     * @Route("/{activity_id}.{_format}",requirements={"pagina" = "\d+"}, options={"expose"=true}, defaults={"_format"="json"})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("is_granted('ROLE_UAH_STUDENT')")
     */
    public function enrollAction(Activity $activity, Request $request)
    {
        /* @var $enrollmentService \UAH\GestorActividadesBundle\Services\EnrollmentService */
        $enrollmentService = $this->get('uah.services.enrollment_service');
        $response = $enrollmentService->createEnrollment($activity, $this->getUser());
        return $this->get('uah.services.response_handling')->createJSONResponse($response);
    }

    /**
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @param \Symfony\Component\HttpFoundation\Request    $request
     * @Route("/recognize/{activity_id}.{_format}",requirements={"activity_id" = "\d+"}, options={"expose"=true}, defaults={"_format"="json"})
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
            $response = $enrollmentService->recognizeEnrollments($recognizements, $activity, $this->getUser());
            return $this->get('uah.services.response_handling')->createJSONResponse($response);
        } else {
            return $this->get('uah.services.invalid_token_response')->generateInvalidCSRFTokenResponse('profile');
        }
    }

    /**
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @param \Symfony\Component\HttpFoundation\Request      $request
     *
     * @Route("/unrecognize/{activity_id}.{_format}",requirements={"activity_id" = "\d+"}, options={"expose"=true}, defaults={"_format"="json"})
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
            $response = $enrollmentService->unrecognizeEnrollments($id_unrecognizements, $activity);
            return $this->get('uah.services.response_handling')->createJSONResponse($response);
        } else {
            return $this->get('uah.services.invalid_token_response')->generateInvalidCSRFTokenResponse('profile');
        }
    }

    /**
     *
     * @param \UAH\GestorActividadesBundle\Entity\Enrollment $enrollment
     * @param \Symfony\Component\HttpFoundation\Request      $request
     * @Route("/unenroll/{enrollment_id}.{_format}", requirements={"enrollment_id" = "\d+"}, options={"expose" = true}, defaults={"_format"="json"})
     * @ParamConverter("enrollment",  class="UAHGestorActividadesBundle:Enrollment",options={"id" = "enrollment_id"}))
     * @Security("enrollment.getUser() === user")
     */
    public function unenrollAction(Enrollment $enrollment, Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('profile', $request->headers->get('X-CSRFToken'))) {
            /* @var $enrollmentService \UAH\GestorActividadesBundle\Services\EnrollmentService */
            $enrollmentService = $this->get('uah.services.enrollment_service');
            $response = $enrollmentService->removeEnrollment($enrollment);
            return $this->get('uah.services.response_handling')->createJSONResponse($response);
        } else {
            return $this->get('uah.services.invalid_token_response')->generateInvalidCSRFTokenResponse('profile');
        }
    }

}
