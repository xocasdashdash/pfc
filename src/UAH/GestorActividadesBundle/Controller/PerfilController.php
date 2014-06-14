<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UAH\GestorActividadesBundle\Entity\User as User;

class PerfilController extends Controller {

    /**
     * @Route("/perfil")
     * @Method({"GET"})
     * @Security("is_fully_authenticated()")
     */
    public function indexAction() {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if ($user) {
            $degree = $user->getDegreeId();
        }
        $roles = $this->getUser()->getUserRoles();
        //var_dump($roles);
        $enrolled_activities = $em->getRepository('UAHGestorActividadesBundle:Enrollment')
                ->getEnrolledActivities($this->getUser(), 1);
        return $this->render('UAHGestorActividadesBundle:Perfil:index.html.twig', array('user' => $user,
                    'degree' => $degree,
                    'enrolled_activities' => $enrolled_activities,
                    'roles' => $roles));
    }

    /**
     * @Route("/perfil")
     * @Method({"POST"})
     */
    public function editAction() {
        
    }

}
