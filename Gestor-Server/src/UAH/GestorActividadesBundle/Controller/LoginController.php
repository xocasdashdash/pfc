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

class LoginController extends Controller {

    /**
     * 
     * @Route("/login/{after_login}/{openid_identifier}", defaults={"after_login" = " ","openid_identifier" = " " })
     */
    public function loginAction($after_login, $openid_identifier) {
        if ($openid_identifier === " " &&
                $this->container->hasParameter("default_openid_connector")) {
            $openid_identifier = $this->container->getParameter("default_openid_connector");
        } else {
            throw new RuntimeException("Falta configurar el parametro default_openid_connector");
        }
        if ($after_login === " " &&
                $this->container->hasParameter("default_redirect_after_login")) {
            $after_login = $this->container->getParameter("default_redirect_after_login");
        } else {
            throw new RuntimeException("Falta configurar el parametro default_redirect_after_login");
        }

        return $this->redirect($this->generateUrl("fp_openid_security_check", array('openid_identifier' => $openid_identifier,
                            '_target_path' => $after_login)), 301);
    }

    /**
     * @Route("/logout")
     */
    public function logoutAction() {
        return $this->redirect("fp_openid_security_logout");
    }

}
