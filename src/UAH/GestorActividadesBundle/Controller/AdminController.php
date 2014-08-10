<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/activities/printpending")
     * @Security("has_role('ROLE_UAH_ADMIN')")
     */
    public function printpendingAction(Request $request) {
        
    }

}
