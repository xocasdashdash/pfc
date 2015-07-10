<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UAH\GestorActividadesBundle\Entity\User;
use UAH\GestorActividadesBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * @Route("/profile")
 */
class ProfileController extends Controller
{
    /**
     * @Route("/")
     * @Method({"GET"})
     * @Security("is_fully_authenticated()")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if ($user) {
            $degree = $user->getDegree();
        }
        $roles = $this->getUser()->getUserRoles();
        $enrolled_activities = $em->getRepository('UAHGestorActividadesBundle:Enrollment')
                ->getEnrolledActivities($this->getUser(), 1);
        $response = $this->render('UAHGestorActividadesBundle:Profile:index.html.twig', array('user' => $user,
            'degree' => $degree,
            'enrolled_activities' => $enrolled_activities,
            'roles' => $roles, ));
        $token = $this->get('form.csrf_provider')->generateCsrfToken('profile');
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * @Route("/update")
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $degrees = $em->getRepository('UAHGestorActividadesBundle:Degree')->getActive();
        $form = $this->createForm(new UserType(), $this->getUser(), array('degrees' => $degrees));
        $form->handleRequest($request);
        if ($form->isValid()) {
            /* @var $user User */
            $user = $form->getData();
            $user->setCreationIp($request->getClientIp());
            $user->setDateCreated(new \DateTime());
            $user->setDateUpdated(new \DateTime());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl("uah_gestoractividades_profile_index"));
        }

        return $this->render('UAHGestorActividadesBundle:Profile:update.html.twig', array(
                    'form' => $form->createView(), ));
    }

    /**
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @param \Symfony\Component\HttpFoundation\Request    $request
     * @Route("/activities/",options={"expose"=true})
     * @Route("/activities/{user_id}", requirements={"user_id" = "\d+"}, defaults={"user_id"=-1}, options={"expose"=true})
     * @Security("is_granted('ROLE_UAH_STAFF_PDI') || is_granted('ROLE_UAH_ADMIN')")
     */
    public function myactivitiesAction($user_id = -1, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if (($user_id !== -1)) {
            if ($this->get('security.context')->isGranted('ROLE_UAH_ADMIN')) {
                $user = $em->getRepository('UAHGestorActividadesBundle:User')->find($user_id);
                $activities = $user->getActivities();
            } else {
                throw new AccessDeniedException('No tienes permiso para ver estas actividades');
            }
        } else {
            if ($request->query->get('filter', 'none') === 'none') {
                $activities = $em->getRepository('UAHGestorActividadesBundle:Activity')->getNotClosedActivities($this->getUser());
            } else {
                $activities = $this->getUser()->getActivities();
            }
        }
        $response = $this->render('UAHGestorActividadesBundle:Profile:myactivities.html.twig', array(
            'activities' => $activities, ));
        $token = $this->get('form.csrf_provider')->generateCsrfToken('profile');
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $response->headers->setCookie($cookie);

        return $response;
    }
}
