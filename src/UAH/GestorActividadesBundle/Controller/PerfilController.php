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
        $user = $this->get('security.context')->getToken()->getUser();
        if ($user) {
            $degree = $user->getDegreeId();
        }
        return $this->render('UAHGestorActividadesBundle:Perfil:index.html.twig', array('user' => $user,
                    'degree' => $degree,
            'items' => array()));
    }

    /**
     * @Route("/perfil")
     * @Method({"POST"})
     */
    public function editAction() {
        
    }

}
