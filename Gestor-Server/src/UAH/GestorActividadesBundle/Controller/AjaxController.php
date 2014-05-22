<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AjaxController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UAHGestorActividadesBundle:Default:index.html.twig', array('name' => $name));
    }
}
