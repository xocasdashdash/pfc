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
        $activities = $activity_repository->getPublishedActivities($pagina, 1); //findBy(array(), array('publicityStartDate' => 'ASC'));
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
        $activities = $activity_repository->getPublishedActivities($page, 1);
        $enrolled_activities = $em->getRepository('UAHGestorActividadesBundle:Enrollment')->getEnrolledActivitiesId($this->getUser(), $page);
        $html = $this->renderView('UAHGestorActividadesBundle:Activity:activity-pagination.html.twig', array(
            'activities' => $activities,
            'enrollments' => $enrolled_activities));
        $response = array();
        $response['html'] = $html;
        return new JsonResponse($response);
    }

}
