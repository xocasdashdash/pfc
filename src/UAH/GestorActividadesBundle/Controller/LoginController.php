<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    const LOGIN_ERROR_NOT_LOGGED_IN = 1;

    /**
     *
     * @Route("/login")
     */
    public function loginAction(Request $request)
    {
        if ($request->isXmlHttpRequest() && !$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $response = array();
            $response['message'] = 'NOT_LOGGED_IN';
            $response['status'] = 'error';
            $response['code'] = self::LOGIN_ERROR_NOT_LOGGED_IN;

            return new \Symfony\Component\HttpFoundation\JsonResponse($response, 401);
        }
        $openid_identifier = $this->container->getParameter("default_openid_connector");

        return $this->redirect($this->generateUrl("fp_openid_security_check", array('openid_identifier' => $openid_identifier), "301"));
    }

    /**
     * @Route("/logout")
     */
    public function logoutAction()
    {
        return $this->redirect("fp_openid_security_logout");
    }
}
