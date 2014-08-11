<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * @Route("/admin")
 */
class AdminController extends Controller {

    /**
     * 
     * @Security("has_role('ROLE_UAH_ADMIN')")
     */
    public function indexAction() {
        return new \Symfony\Component\HttpFoundation\Response("blank");
    }

    /**
     * @Route("/activities",options={"expose"=true})
     * @Security("has_role('ROLE_UAH_ADMIN')")
     */
    public function activitiesAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->get('filter', 'pending') === 'pending') {
            $activities = $em->getRepository('UAHGestorActividadesBundle:Activity')->getPending();
        } else {
            $activities = $em->getRepository('UAHGestorActividadesBundle:Activity')->getAll();
        }
        $token = $this->get('form.csrf_provider')->generateCsrfToken('uah_admin');
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $response = $this->render('UAHGestorActividadesBundle:Admin:activities.html.twig', array('activities' => $activities));
        $response->headers->setCookie($cookie);
        return $response;
    }

    /**
     * @Route("/users")
     * @Security("has_role('ROLE_UAH_ADMIN')")
     */
    public function usersAction() {
        
    }

    /**
     * @Route("/activities/approve",options={"expose"=true})
     * @Security("has_role('ROLE_UAH_ADMIN')")
     */
    public function approveAction(Request $request) {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('uah_admin', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();
            $status_approved = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getApproved();
            $status_pending = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getPending();
            $activities = $em->getRepository('UAHGestorActividadesBundle:Activity')->getActivitiesByID(json_decode($request->getContent()));
            $response = array();
            foreach ($activities as $activity) {
                if ($activity->getStatus() === $status_pending) {
                    $activity->setStatus($status_approved);
                    $em->persist($activity);
                    $valid_data = true;
                } else {
                    $valid_data = false;
                    break;
                }
            }
            if ($valid_data) {
                $em->flush();
                $response['type'] = 'success';
                $response['message'] = 'OK';
                return new JsonResponse($response, 200);
            } else {
                $response['type'] = 'error';
                $response['message'] = 'Ha habido un problema al realizar la solicitud';
                return new JsonResponse($response, 400);
            }
        } else {
            $response['type'] = 'error';
            $response['message'] = 'Hay un problema con el token CSRF. Prueba a recargar la página';
            return new JsonResponse($response, 400);
        }
    }

    /**
     * @Route("/activities/printpending",options={"expose"=true})
     * @Security("has_role('ROLE_UAH_ADMIN')")
     */
    public function printpendingAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository('UAHGestorActividadesBundle:Activity')->getPending();

        $activities_url = array();
        foreach ($activities as $activity) {
            $url = $this->generateUrl('uah_gestoractividades_activity_index', array(
                'activity_id' => $activity['id']), true);
            $activities_url[] = $url;
        }
        if (!empty($activities_url)) {
            $pdf_dir = $this->container->getParameter('pdf_dir');
            $result = $this->get('knp_snappy.pdf')->generate($activities_url, $pdf_dir . 'Report.pdf', array(), true);
            if (is_null($result)) {
                $content = file_get_contents($pdf_dir . 'Report.pdf');
                $response = new Response();
                //set headers
                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-Type', 'application/download');
                $response->headers->set('Content-Length', filesize($pdf_dir . 'Report.pdf'));
                $response->headers->set('Content-Disposition', 'attachment;filename="Informe de actividades pendientes de aprobar.pdf"');
                $response->setContent($content);
                $response->setStatusCode(200);
            } else {
                $response = new JsonResponse('Error', 400);
            }
        } else {
            $response = new JsonResponse('Error', 400);
        }
        return $response;
    }

}
