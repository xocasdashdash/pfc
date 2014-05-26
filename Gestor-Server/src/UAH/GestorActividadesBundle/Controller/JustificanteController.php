<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class JustificanteController extends Controller {
    /**
     * @Route("/justificante")
     * @Method({"GET"})
     */
    public function indexAction() {      
    }    
    /**
     * @Route("/justificante/show/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1})
     * @Method({"GET"})
     */
    public function showAction($id) {      
    }    
    /**
     * @Route("/justificante")
     * @Method({"POST"})
     */
    public function createAction() {      
    }
        
    /**
     * @Route("/justificante/delete/{id}", requirements={"id" = "\d+"}, defaults={"id" = -1})
     * @Method({"POST"})
     */
    public function deleteAction($id) {      
    }    
    /**
     * @Route("/justificante/hide/{id}")
     * @Method({"POST"})
     */    
    public function hideAction($id, $slug) {      
    }

}
