<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UAH\GestorActividadesBundle\Entity\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/application")
 */
class ApplicationController extends Controller
{

    /**
     * @Route(options={"expose"=true})
     * @Security("is_fully_authenticated()")
     */
    public function indexAction(Request $request)
    {
        $filter = $request->query->get('filter', 'not_archived');
        $applications = $this->getDoctrine()
                ->getManager()->getRepository('UAHGestorActividadesBundle:Application')
                ->getUserApplications($this->getUser(), $filter);
        $response = $this->render('UAHGestorActividadesBundle:Application:index.html.twig', array(
            'applications' => $applications,));
        $token = $this->get('form.csrf_provider')->generateCsrfToken('application');
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $response->headers->setCookie($cookie);
        return $response;
    }

    /**
     * @Route("/show/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1}, options={"expose"=true})
     * @ParamConverter("application", class="UAHGestorActividadesBundle:Application")
     */
    public function showAction(Application $application)
    {
        $name = $this->getUser()->getName();
        $apellido1 = $this->getUser()->getApellido1();
        $apellido2 = $this->getUser()->getApellido2();
        $degree = $this->getUser()->getDegree()->getName();
        $show_verify = ($this->get('security.context')->isGranted('ROLE_UAH_STAFF_PAS') &&
                $application->getStatus() === $this->getDoctrine()->getManager()->getRepository('UAHGestorActividadesBundle:Statusapplication')->getDefault());

        return $this->render('UAHGestorActividadesBundle:Application:show.html.twig', array(
                    'application' => $application,
                    'enrollments' => $application->getEnrollments(),
                    'typeOfCredits' => $application->getTypeOfCredits(),
                    'name' => $name,
                    'apellido1' => $apellido1,
                    'apellido2' => $apellido2,
                    'degree' => $degree,
                    'show_verify' => $show_verify,
        ));
    }

    /**
     *
     * @Route("/create",options={"expose"=true})
     * @Security("is_granted('ROLE_UAH_STUDENT')")
     */
    public function createAction(Request $request)
    {
        $response = array();
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('profile', $request->headers->get('X-CSRFToken'))) {
            $enrollment_ids = json_decode($request->getContent(), true);
            if ($enrollment_ids === false) {
                $response['type'] = 'error';
                $response['message'] = 'Mensaje erroneo';
                return new JsonResponse($response, 400);
            }
            /* @var $applicationService \UAH\GestorActividadesBundle\Services\ApplicationService */
            $applicationService = $this->get('uah.services.application_service');
            try {
                $application = $applicationService->createApplication($enrollment_ids, $this->getUser());
                $response['type'] = 'success';
                $response['code'] = 0;
                $response['message'] = $application->getId();
                return new JsonResponse($response, 200);
            } catch (\Exception $ex) {
                return $this->get('uah.services.response_from_exception')->generateResponseFromException($ex);
            }
        } else {
            return $this->get('uah.services.invalid_token_response')->generateInvalidCSRFTokenResponse('application');
        }
    }

    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1}, options={"expose"=true}))
     * @ParamConverter("application", class="UAHGestorActividadesBundle:Application")
     */
    public function deleteAction(Application $application, Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('application', $request->headers->get('X-CSRFToken'))) {
            /* @var $applicationService \UAH\GestorActividadesBundle\Services\ApplicationService */
            $applicationService = $this->get('uah.services.application_service');
            try {
                $applicationService->deleteApplication($application, $this->getUser());
                $response = array();
                $response['code'] = 0;
                $response['message'] = 'Justificante borrado!';
                $response['type'] = 'success';
                return new JsonResponse($respuesta_json);
            } catch (Exception $ex) {
                return $this->get('uah.services.response_from_exception')->generateResponseFromException($ex);
            }
        }
    }

    /**
     * @Route("/archive/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1}, options={"expose"=true}))
     * @ParamConverter("application", class="UAHGestorActividadesBundle:Application")
     */
    public function archiveAction(Application $application, Request $request)
    {
        $response = array();
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('application', $request->headers->get('X-CSRFToken'))) {
            /* @var $applicationService \UAH\GestorActividadesBundle\Services\ApplicationService */
            $applicationService = $this->get('uah.services.application_service');
            try {
                $applicationService->archiveApplication($application, $this->getUser());
                $response['code'] = 0;
                $response['message'] = 'Justificante archivado!';
                $response['type'] = 'success';
                return new JsonResponse($response);
            } catch (Exception $ex) {
                return $this->get('uah.services.response_from_exception')->generateResponseFromException($ex);
            }
        } else {
            return $this->get('uah.services.invalid_token_response')->generateInvalidCSRFTokenResponse('application');
        }
    }

    /**
     * @Route("/check_code/{applicationCode}", defaults={"applicationCode" = -1}, options={"expose"=true}))
     * @Security("is_granted('ROLE_UAH_STAFF_PAS')")
     */
    public function checkCodeAction($applicationCode)
    {
        $em = $this->getDoctrine()->getManager();
        $applicationRepository = $em->getRepository('UAHGestorActividadesBundle:Application');
        $applicationDefaultStatus = $em->getRepository('UAHGestorActividadesBundle:Statusapplication')->getDefault();
        $app = $applicationRepository->findOneBy(array('verificationCode' => $applicationCode,
            'status' => $applicationDefaultStatus,));
        $response = array();
        if ($app) {
            $response['code'] = 200;
            $response['message'] = $this->generateUrl(
                    'uah_gestoractividades_application_show', array('id' => $app->getId())
            );
            $response['type'] = 'success';
        } else {
            $response['code'] = 400;
            $response['message'] = 'Justificante no encontrado';
            $response['type'] = 'error';
        }

        return new JsonResponse($response, $response['code']);
    }

    /**
     * @Route("/verify/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1}, options={"expose"=true}))
     * @ParamConverter("application", class="UAHGestorActividadesBundle:Application")
     * @Security("is_granted('ROLE_UAH_STAFF_PAS')")
     */
    public function verifyAppAction(Application $application, Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('application', $request->headers->get('X-CSRFToken'))) {
            /* @var $applicationService \UAH\GestorActividadesBundle\Services\ApplicationService */
            $applicationService = $this->get('uah.services.application_service');
            $response = array();
            try {
                $applicationService->archiveApplication($application, $this->getUser());
                $response['code'] = 0;
                $response['message'] = 'Justificante verificado';
                $response['type'] = 'success';
                return new JsonResponse($response);
            } catch (Exception $ex) {
                return $this->get('uah.services.response_from_exception')->generateResponseFromException($ex);
            }
        } else {
            return $this->get('uah.services.invalid_token_response')->generateInvalidCSRFTokenResponse('application');
        }

        return new JsonResponse($response, $response['code']);
    }

}
