<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AjaxController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UAHGestorActividadesBundle:Default:index.html.twig', array('name' => $name));
    }
}
