<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller {

    /**
     * @Route("/{pagina}", requirements={"pagina" = "\d+"}, defaults={"pagina" = 1},options={"expose"=true}))
     * @Route("/pag/{pagina}", requirements={"pagina" = "\d+"}, defaults={"pagina" = 1})
     */
    public function indexAction($pagina, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $activity_repository = $em->getRepository('UAHGestorActividadesBundle:Activity');
        $category_repository = $em->getRepository('UAHGestorActividadesBundle:Category');
        $activity_repository->updatePublished();
        $elements_per_page = $this->container->getParameter('elements_per_page');
        if ($pagina <= 0) {
            $pagina = 1;
        }
        $activities = null;//$activity_repository->getPublishedActivities($pagina, $elements_per_page);
        $categories = $category_repository->getFrontPage();
        $num_activities = $activity_repository->getCountPublishedActivities();
        $enrolled_activities = $em->getRepository('UAHGestorActividadesBundle:Enrollment')->getEnrolledActivitiesId($this->getUser(), $pagina);
        return $this->render('UAHGestorActividadesBundle:Default:index.html.twig', array(
                    'activities' => $activities,
                    'enrollments' => $enrolled_activities,
                    'categories' => $categories,
                    'num_activities' => $num_activities));
    }

    /**
     * @Route("/ajax/{page}", requirements={"pagina" = "\d+"}, defaults={"page" = 1},options={"expose"=true}))
     */
    public function ajaxActivities($page) {
        $em = $this->getDoctrine()->getManager();
        $activity_repository = $em->getRepository('UAHGestorActividadesBundle:Activity');
        $elements_per_page = $this->container->getParameter('elements_per_page');
        $activities = $activity_repository->getPublishedActivities($page, $elements_per_page);
        $enrolled_activities = $em->getRepository('UAHGestorActividadesBundle:Enrollment')->getEnrolledActivitiesId($this->getUser(), $page);
        $html = $this->renderView('UAHGestorActividadesBundle:Activity:activity-pagination.html.twig', array(
            'activities' => $activities,
            'enrollments' => $enrolled_activities));
        $response = array();
        $response['html'] = $html;
        $response['last_page'] = count($activities) < $elements_per_page;
        return new JsonResponse($response);
    }

}
