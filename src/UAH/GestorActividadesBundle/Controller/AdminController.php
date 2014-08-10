<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
    public function activitiesAction() {
        
    }

    /**
     * @Route("/users")
     * @Security("has_role('ROLE_UAH_ADMIN')")
     */
    public function usersAction() {
        
    }

}
