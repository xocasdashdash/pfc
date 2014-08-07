<?php

namespace UAH\GestorActividadesBundle\Services;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface {

    protected $router;
    protected $security;

    public function __construct(Router $router, SecurityContext $security) {
        $this->router = $router;
        $this->security = $security;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {

        $user = $token->getUser();
        if (!$user->isProfileComplete()) {
            $url = $this->router->generate('uah_gestoractividades_profile_edit');
        } else {
            $url = $request->headers->get('referer');
        }
        $response = new RedirectResponse($url);

        return $response;
    }

}
