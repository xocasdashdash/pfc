<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ActividadController extends Controller {
    
    /**
     * @Route("/actividad")
     * @Method({"GET"})
     */
    public function indexAction($id) {      
    }
    
    /**
     * @Route("/actividad")
     * @Route("/actividad/create", name="uah_gestoractividades_actividad_create_form")
     * @Method({"GET,POST"})
     * @Security("has_role=('ROLE_ORGANIZER')")
     */
    public function createAction() {      
    }
    
    /**
     * @Route("/actividad/edit/{id}", requirements={"id" = "\d+"}, defaults={"id"=-1})
     * @Method({"POST"})
     * @Security("has_role=('ROLE_ORGANIZER')")
     */
    public function editAction() {      
    }
    
    /**
     * @Route("/actividad/{id}/{slug}", requirements={"id" = "\d+"}, defaults={"id"=-1, "slug"="none"})
     * @Method({"GET"})
     * @param type $id
     * @param type $slug
     */
    public function showAction($id, $slug) {      
    }
    
    /**
     * @Route("/actividad/manage/{id}", requirements={"id" = "\d+"}, defaults={"id"=-1})
     * @Method({"POST,GET"})
     * @Security("has_role=('ROLE_ORGANIZER')")
     */
    public function manageAction($id) {      
    }
    
    /**
     * @Route("/actividad/enroll/{id}", requirements={"id" = "\d+"}, defaults={"id"=-1})
     * @Method({"POST"})
     * @Security("has_role=('ROLE_USER')")
     */
    public function enrollAction($id) {      
    }

}
