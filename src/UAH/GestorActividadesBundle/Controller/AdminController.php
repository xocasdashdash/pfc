<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
class AdminController extends Controller
{    
    /**
     * @Route("/admin")
     * @Security("has_role('ROLE_UAH_ADMIN')")
     */
    public function indexAction()
    {
        return new \Symfony\Component\HttpFoundation\Response("blank");
    }
}
