<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    /**
     * @Route("/{pagina}", requirements={"pagina" = "\d+"}, defaults={"pagina" = 1},options={"expose"=true}))
     * @Route("/pag/{pagina}", requirements={"pagina" = "\d+"}, defaults={"pagina" = 1})
     */
    public function indexAction($pagina, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository('UAHGestorActividadesBundle:Activity')->getPublishedActivities();//findBy(array(), array('publicityStartDate' => 'ASC'));
        $num_actividades = count($activities);
        $enrollments_id = array();
        $enrolled_activities = $em->getRepository('UAHGestorActividadesBundle:Enrollment')->getEnrolledActivitiesId($this->getUser(), $pagina);
        return $this->render('UAHGestorActividadesBundle:Default:index.html.twig', array(
                    'activities' => $activities,
                    'enrollments' => $enrolled_activities));
    }

}
