<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
class DefaultController extends Controller
{
    /**
     * @Route("/{pagina}", requirements={"pagina" = "\d+"}, defaults={"pagina" = 1})
     * @Route("/pag/{pagina}", requirements={"pagina" = "\d+"}, defaults={"pagina" = 1})
     */
    public function indexAction($pagina, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository('UAHGestorActividadesBundle:Activity')->findBy(array(), array('publicityStartDate' => 'ASC'));
        $num_actividades = count($activities);
        $enrollments_id = array();
        $enrolled_activities = $em->getRepository('UAHGestorActividadesBundle:Enrollment')->getEnrolledActivities($this->getUser(), $pagina);
        foreach ($enrolled_activities as $key => $enrolled_activity){
            $enrollments_id[$key] = $enrolled_activity->getId();
        }
        return $this->render('UAHGestorActividadesBundle:Default:index.html.twig', array(
            'activities' => $activities, 
            'enrollments' =>$enrollments_id));
    }
}
