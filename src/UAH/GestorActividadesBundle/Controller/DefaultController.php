<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/{page}", requirements={"page" = "\d+"}, defaults={"page" = 1},options={"expose"=true}))
     * @Route("/pag/{page}", requirements={"page" = "\d+"}, defaults={"page" = 1})
     */
    public function indexAction($page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $activity_repository \UAH\GestorActividadesBundle\Repository\ActivityRepository*/
        $activity_repository = $em->getRepository('UAHGestorActividadesBundle:Activity');
        $category_repository = $em->getRepository('UAHGestorActividadesBundle:Category');
        $activity_repository->updatePublished();
        $elements_per_page = $this->container->getParameter('elements_per_page');
        $activities = $activity_repository->getPublishedActivities($page, $elements_per_page);
        $categories = $category_repository->getFrontPage();
        $num_activities = $activity_repository->getCountPublishedActivities();
        $enrolled_activities = $em->getRepository('UAHGestorActividadesBundle:Enrollment')
                ->getEnrolledActivitiesId($this->getUser(), $page);
        $session = $this->get("session");
        $session->set("num_pagina", 0);

        return $this->render('UAHGestorActividadesBundle:Default:index.html.twig', array(
                    'activities' => $activities,
                    'enrollments' => $enrolled_activities,
                    'categories' => $categories,
                    'num_activities' => $num_activities, ));
    }

    /**
     * @Route("/ajax/{page}.{_format}", requirements={"pagina" = "\d+"}, defaults={"page" = 1, "_format"= "json"},options={"expose"=true}))
     */
    public function ajaxActivities($page)
    {
        $em = $this->getDoctrine()->getManager();
        $activity_repository = $em->getRepository('UAHGestorActividadesBundle:Activity');
        $elements_per_page = $this->container->getParameter('elements_per_page');
        $activities = $activity_repository->getPublishedActivities($page, $elements_per_page);
        $enrolled_activities = $em->getRepository('UAHGestorActividadesBundle:Enrollment')->getEnrolledActivitiesId($this->getUser(), $page);
        $html = $this->renderView('UAHGestorActividadesBundle:Activity:activity-pagination.html.twig', array(
            'activities' => $activities,
            'enrollments' => $enrolled_activities, ));
        $response = array();
        $response['html'] = $html;
        $response['last_page'] = count($activities) < $elements_per_page;

        return new JsonResponse($response);
    }

    /**
     * @Route("/search")
     */
    public function searchAction(Request $request)
    {
        $query = $request->get('q');
        $em = $this->getDoctrine()->getManager();
        $activity_repository = $em->getRepository('UAHGestorActividadesBundle:Activity');
        $enrolled_activities = $em->getRepository('UAHGestorActividadesBundle:Enrollment')->getEnrolledActivitiesId($this->getUser());
        $category_repository = $em->getRepository('UAHGestorActividadesBundle:Category');
        $categories = $category_repository->getFrontPage();
        $activities = $activity_repository->searchActivities($query);

        return $this->render('UAHGestorActividadesBundle:Activity:activity-search.html.twig', array(
                    'activities' => $activities,
                    'enrollments' => $enrolled_activities,
                    'categories' => $categories,
                    'query' => $query, ));
    }
}
