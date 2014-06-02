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
     * @Route("/login/{after_login}/{openid_identifier}", defaults={"after_login" = " ","openid_identifier" = " " })
     */
    public function loginAction(Request $request, $after_login, $openid_identifier) {
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
