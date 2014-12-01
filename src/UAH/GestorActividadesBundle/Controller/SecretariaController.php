<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SecretariaController extends Controller
{
    /**
     * @Route("/secretaria")
     * @Method({"POST, GET"})
     * @Security("has_role('ROLE_SECRETARIA')")
     */
    public function indexAction()
    {
    }
    /**
     * @Route("/secretaria/{codigo}",requirements={"id" = "\d+"}, defaults={"id"=1})
     * @Security("has_role('ROLE_SECRETARIA')")
     * @Method({"POST"})
     */
    public function checkAction($codigo)
    {
    }
}
