<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $name = 'pepe';
        return $this->render('UAHGestorActividadesBundle:Default:index.html.twig', array('name' => $name));
    }
}
