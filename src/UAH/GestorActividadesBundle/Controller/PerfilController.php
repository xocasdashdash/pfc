<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use UAH\GestorActividadesBundle\Entity\User as User;
class PerfilController extends Controller {
    /**
     * @Route("/perfil")
     * @Method({"GET"})
     */
    public function indexAction() {
        $user = $this->get('security.context')->getToken()->getUser();
        $degree = $user->getDegreeId();
        return $this->render('UAHGestorActividadesBundle:Perfil:index.html.twig', array('user' => $user,
            'degree' => $degree,));
    }
    /**
     * @Route("/perfil")
     * @Method({"POST"})
     */
    public function editAction() {      
    }

}
