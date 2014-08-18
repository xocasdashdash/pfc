<?php

namespace UAH\GestorActividadesBundle\Services;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface {

    protected $router;
    protected $security;
    protected $em;

    public function __construct(Router $router, SecurityContext $security, EntityManager $entityManager) {
        $this->router = $router;
        $this->security = $security;
        $this->em = $entityManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {

        $user = $token->getUser();
        if (!$user->isProfileComplete()) {
            $url = $this->router->generate('uah_gestoractividades_profile_edit');
        } else {
            $url = $request->headers->get('referer');
        }
        //Actualizo los permisos a los que haya en default permits
        $identity = $user->getIdUsuldap();
        $default_permit = $this->em->getRepository('UAHGestorActividadesBundle:DefaultPermit')->findOneBy(
                array('id_usuldap' => $identity));
        $default_roles = $default_permit->getRoles();
        $user_roles = $user->getUserRoles()->toArray();
        foreach ($default_roles as $default_role) {
            if (!in_array($default_role, $user_roles)) {
                $user->addRole($default_role);
                $this->em->persist($user);
            }
        }
        $this->em->flush();
        $response = new RedirectResponse($url);

        return $response;
    }

}
