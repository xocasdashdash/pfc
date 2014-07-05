<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use UAH\GestorActividadesBundle\Form\ActivityType as ActivityType;
use UAH\GestorActividadesBundle\Entity\Activity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

class ActividadController extends Controller {

    /**
     * @Route("/actividad/{activity_id}-{slug}",requirements={"activity_id" = "\d+"}, defaults={"slug" = ""}, options={"expose"=true}))
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Method({"GET"})
     */
    public function indexAction(Activity $activity, Request $request) {
        if ($request->isXmlHttpRequest()) {
            return new JSONResponse($activity);
        } else {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            //Uso bitmasks para saber que tipo de error hay (si lo hay)
            if ($user &&  $activity) {
                $check_enrolled = $em->getRepository('UAHGestorActividadesBundle:Enrollment')->checkEnrolled($user, $activity);
            } else {
                $check_enrolled = 1;
            }
            //Si el numero de plazas ofertadas es null siempre puedo inscribirme por esto
            $free_places = (is_null($activity->getNumberOfPlacesOffered()) ||
                    $activity->getNumberOfPlacesOccupied() >= $activity->getNumberOfPlacesOffered()) << 1;
            $can_enroll = !($em->getRepository('UAHGestorActividadesBundle:Enrollment')->canEnroll($activity)) << 2;
            $permissions = 0;
            $permissions |= $check_enrolled;
            $permissions |= $free_places;
            $permissions |= $can_enroll;
            if ($permissions != 0) {
                $show_enrollment = 0;
            } else {
                $show_enrollment = 1;
            }
            return $this->render('UAHGestorActividadesBundle:Actividad:index.html.twig', array(
                        'activity' => $activity,
                        'show_enrollment' => $show_enrollment
            ));
        }
    }

    /**
     * @Route("/actividad/create", name="uah_gestoractividades_actividad_create_form")
     * @Method({"GET","POST"})
     * @Security("has_role('ROLE_UAH_STAFF_PDI')")
     */
    public function createAction(Request $request) {
        $activity = new \UAH\GestorActividadesBundle\Entity\Activity();
        $form = $this->createForm(new ActivityType(), $activity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $activity = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $activity->setOrganizer($this->getUser());
            $activity->setNumberOfPlacesOccupied(0);
            $em->persist($activity);
            $em->flush();
            return $this->redirect($this->generateUrl("uah_gestoractividades_default_index"));
        }
        return $this->render('UAHGestorActividadesBundle:Actividad:create.html.twig', array(
                    'form' => $form->createView()));
    }

    /**
     * @Route("/actividad/edit/{activity_id}", requirements={"activity_id" = "\d+"}, defaults={"activity_id"=-1})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && has_role('ROLE_UAH_STAFF_PDI')) || has_role('ROLE_UAH_ADMIN')")
     */
    public function editAction(Activity $activity, Request $request) {
//        if (false === $this->get('security.context')->isGranted('edit_activity', $activity)) {
//            throw new AccessDeniedException('Unauthorised access!');
//        }
        $form = $this->createForm(new ActivityType(), $activity
                , array(
            'edit' => true
        ));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $activity->setOrganizer($this->getUser());
            $em->persist($activity);
            $em->flush();
            return $this->redirect($this->generateUrl("uah_gestoractividades_actividad_index", array('activity_id' => $activity->getId(), 'slug' => $activity->getSlug())));
        }
        return $this->render('UAHGestorActividadesBundle:Actividad:edit.html.twig', array(
                    'form' => $form->createView(),
                    'activity' => $activity));
    }

    /**
     * @Route("/actividad/update/{activity_id}", requirements={"activity_id" = "\d+"}, defaults={"activity_id"=-1})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && has_role('ROLE_UAH_STAFF_PDI')) || has_role('ROLE_UAH_ADMIN')")
     */
    public function updateAction(Activity $activity, Request $request) {
        $editForm = $this->createForm(new ActivityType(), $activity);
        $rutaFotoOriginal = $editForm->getData()->getImagePath();

        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->persist($activity);
            $em->flush();
            return $this->redirect($this->generateUrl("uah_gestoractividades_actividad_index", array('activity_id' => $activity->getId())));
        } else {
            return $this->render('UAHGestorActividadesBundle:Actividad:edit.html.twig', array(
                        'form' => $editForm->createView()));
        }
    }

    /**
     * @Route("/actividad/admin/{activity_id}", requirements={"activity_id" = "\d+"}, defaults={"activity_id"=-1})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && has_role('ROLE_UAH_STAFF_PDI')) || has_role('ROLE_UAH_ADMIN')")
     */
    public function adminAction(Activity $activity, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UAHGestorActividadesBundle:Enrollment');
        if ($request->query->get('show')) {
            $filter = "enrolled";
        } else {
            $filter = "all";
        }
        $enrollments = $repository->getEnrolledInActivity($activity, $filter);
        $response = $this->render('UAHGestorActividadesBundle:Actividad:admin.html.twig', array(
            'enrollments' => $enrollments,
            'activity' => $activity));
        $token = $this->get('form.csrf_provider')->generateCsrfToken('administracion');
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $response->headers->setCookie($cookie);
        return $response;
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

    /**
     * 
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/actividad/close/{activity_id}", requirements={"activity_id" = "\d+"}, defaults={"activity_id"=-1}, options={"expose"=true})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && has_role('ROLE_UAH_STAFF_PDI')) || has_role('ROLE_UAH_ADMIN')")
     */
    public function closeAction(Activity $activity, Request $request) {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('administracion', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();
            $enrollments = $activity->getEnrollees();
            $status_enrolled = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getEnrolledStatus();
            $status_not_recognized = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getNotRecognizedStatus();

            foreach ($enrollments as $enrollment) {
                if ($enrollment->getStatus() === $status_enrolled) {
                    $enrollment->setStatus($status_not_recognized);
                    $em->persist($enrollment);
                }
            }
            $activity->setStatus($em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getClosedStatus());
            $em->persist($activity);
            $em->flush();
            return new JsonResponse("OK", 200);
        } else {
            $response = array();
            $response['code'] = self::RECOGNIZEMENT_ERROR_CSRF_TOKEN_INVALID;
            $response['message'] = 'El token CSRF no es válido. Recarga la página e inténtalo de nuevo';
            $response['type'] = 'error';
            $json_response = new JsonResponse($response, 403);
            return $json_response;
        }
    }

}
