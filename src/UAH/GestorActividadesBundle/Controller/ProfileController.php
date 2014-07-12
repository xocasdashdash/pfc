<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UAH\GestorActividadesBundle\Entity\User as User;
use Symfony\Component\HttpFoundation\Cookie;

class ProfileController extends Controller {

    /**
     * @Route("/profile")
     * @Method({"GET"})
     * @Security("is_fully_authenticated()")
     */
    public function indexAction() {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if ($user) {
            $degree = $user->getDegreeId();
        }
        $roles = $this->getUser()->getUserRoles();
        $enrolled_activities = $em->getRepository('UAHGestorActividadesBundle:Enrollment')
                ->getEnrolledActivities($this->getUser(), 1);
        $response = $this->render('UAHGestorActividadesBundle:Profile:index.html.twig', array('user' => $user,
                    'degree' => $degree,
                    'enrolled_activities' => $enrolled_activities,
                    'roles' => $roles));
        $token = $this->get('form.csrf_provider')->generateCsrfToken('profile');
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $response->headers->setCookie($cookie);
        return $response;
        
    }

    /**
     * @Route("/profile")
     * @Method({"POST"})
     */
    public function editAction() {
        
    }

}
