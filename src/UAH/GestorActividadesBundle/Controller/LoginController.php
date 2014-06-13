<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\PropertyAccess\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends Controller {

    /**
     * 
     * Route("/login/{after_login}/{openid_identifier}", defaults={"after_login" = " ","openid_identifier" = " " })
     * @Route("/login")
     */
    public function loginAction(Request $request){
        
        if($request->isXmlHttpRequest() && !$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')){
            $respuesta = array();
            $respuesta['message'] = 'NOT_LOGGED_IN';
            $respuesta['status'] = 'error';
            return new \Symfony\Component\HttpFoundation\JsonResponse($respuesta,401);
        }
        $openid_identifier = $this->container->getParameter("default_openid_connector");
        return $this->redirect($this->generateUrl("fp_openid_security_check", array('openid_identifier' => $openid_identifier), "301"));
        /* ,/*'_target_path' => $after_login) */
    }

    /**
     * @Route("/logout")
     */
    public function logoutAction() {
        return $this->redirect("fp_openid_security_logout");
    }

}
