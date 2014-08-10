<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/activities")
     * @Security("has_role('ROLE_UAH_ADMIN')")
     */
    public function activitiesAction(Request $request) {
        return $this->render('UAHGestorActividadesBundle:Admin:activities.html.twig');
    }

    /**
     * @Route("/users")
     * @Security("has_role('ROLE_UAH_ADMIN')")
     */
    public function usersAction() {
        
    }

    /**
     * @Route("/activities/approve")
     * @Security("has_role('ROLE_UAH_ADMIN')")
     */
    public function approveAction(Request $request) {
        
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
        $pdf_dir = $this->container->getParameter('pdf_dir');
        $result = $this->get('knp_snappy.pdf')->generate($activities_url, $pdf_dir . 'Report.pdf', array(), true);
        if (is_null($result)) {
            if ($request->isXmlHttpRequest()) {
                $response = array();
                $response['type'] = 'success';
                $response['message'] = 'a';
                $response = new JsonResponse();
            } else {
                $content = file_get_contents($pdf_dir . 'Report.pdf');
                $response = new Response();

                //set headers
                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-Type', 'application/download');
                $response->headers->set('Content-Length', filesize($pdf_dir . 'Report.pdf'));
                $response->headers->set('Content-Disposition', 'attachment;filename="Informe de actividades pendientes de aprobar.pdf"');
                $response->setContent($content);
                $response->setStatusCode(200);
            }
        } else {
            $response = new JsonResponse('Error', 400);
        }
        return $response;
    }

}
