<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use UAH\GestorActividadesBundle\Form\ActivityType as ActivityType;
use UAH\GestorActividadesBundle\Entity\Activity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/activity")
 */
class ActivityController extends Controller
{
    const ERROR_CSRF_TOKEN_INVALID = 1;
    const APPROVAL_ERROR_NOT_ORGANIZER = 2;
    const APPROVAL_ERROR_INCORRECT_STATUS = 3;
    const APPROVAL_SUCCESSFULLY_ASKED = 4;
    const ACTIVITY_OPENED = 5;

    /**
     * @Route("/{activity_id}-{slug}",requirements={"activity_id" = "\d+"}, defaults={"slug" = ""}, options={"expose"=true}))
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Method({"GET"})
     */
    public function indexAction(Activity $activity, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return new JSONResponse($activity);
        } else {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            //Uso bitmasks para saber que tipo de error hay (si lo hay)
            if ($user && $activity) {
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
            if ($permissions === 0) {
                $permissions = "ENROLLABLE";
            } elseif ($permissions === 1) {
                $permissions = "ENROLLED";
            } elseif ($permissions === 2) {
                $permissions = "NO_PLACES";
            } elseif ($permissions === 4) {
                $permissions = "NOT_ENROLLABLE";
            }

            return $this->render('UAHGestorActividadesBundle:Activity:index.html.twig', array(
                        'activity' => $activity,
                        'permissions' => $permissions,
            ));
        }
    }

    /**
     * @Route("/create",options={"expose"=true})
     * @Method({"GET","POST"})
     * @Security("is_granted('ROLE_UAH_STAFF_PDI')")
     */
    public function createAction(Request $request)
    {
        $activity = new \UAH\GestorActividadesBundle\Entity\Activity();
        $form = $this->createForm(new ActivityType($this->getDoctrine()->getManager()
                        ->getRepository('UAHGestorActividadesBundle:Category')), $activity,
                array('isAdmin' => $this->isGranted('ROLE_UAH_ADMIN'), 'fullyEditable' => true));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $activity = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $activity->setOrganizer($this->getUser());
            $activity->setNumberOfPlacesOccupied(0);
            $em->persist($activity);
            $em->flush();

            return $this->redirect($this->generateUrl("uah_gestoractividades_profile_myactivities"));
        }

        return $this->render('UAHGestorActividadesBundle:Activity:create.html.twig', array(
                    'form' => $form->createView(), 'fullyEditable' => true,
                    'isAdmin' =>$this->isGranted('ROLE_UAH_ADMIN')  ));
    }

    /**
     * @Route("/edit/{activity_id}", requirements={"activity_id" = "\d+"}, defaults={"activity_id"=-1})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && is_granted('ROLE_UAH_STAFF_PDI')) || is_granted('ROLE_UAH_ADMIN')")
     */
    public function editAction(Activity $activity, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fullyEditable = $em->getRepository('UAHGestorActividadesBundle:Activity')->isFullyEditable($activity);
        $form = $this->createForm(new ActivityType($this->getDoctrine()->getManager()
                        ->getRepository('UAHGestorActividadesBundle:Category')), $activity, array(
            'fullyEditable' => $fullyEditable,
            'isAdmin' => $this->isGranted('ROLE_UAH_ADMIN'),
        ));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $activity = $form->getData();
            $em->persist($activity);
            $em->flush();

            return $this->redirect($this->generateUrl("uah_gestoractividades_activity_index", array('activity_id' => $activity->getId(), 'slug' => $activity->getSlug())));
        }

        return $this->render('UAHGestorActividadesBundle:Activity:edit.html.twig', array(
                    'form' => $form->createView(),
                    'activity' => $activity,
                    'fullyEditable' => $fullyEditable,
                    'isAdmin' => $this->get('security.context')->isGranted('ROLE_UAH_ADMIN'), ));
    }

    /**
     * @Route("/update/{activity_id}", requirements={"activity_id" = "\d+"}, defaults={"activity_id"=-1})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && is_granted('ROLE_UAH_STAFF_PDI')) || is_granted('ROLE_UAH_ADMIN')")
     */
    public function updateAction(Activity $activity, Request $request)
    {
        $editForm = $this->createForm(new ActivityType($this->getDoctrine()->getManager()
                        ->getRepository('UAHGestorActividadesBundle:Category')), $activity);
        $rutaFotoOriginal = $editForm->getData()->getImagePath();

        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            return $this->redirect($this->generateUrl("uah_gestoractividades_activity_index", array('activity_id' => $activity->getId())));
        } else {
            return $this->render('UAHGestorActividadesBundle:Activity:edit.html.twig', array(
                        'form' => $editForm->createView(), ));
        }
    }

    /**
     * @Route("/admin/{activity_id}", requirements={"activity_id" = "\d+"}, defaults={"activity_id"=-1})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && is_granted('ROLE_UAH_STAFF_PDI')) || is_granted('ROLE_UAH_ADMIN')")
     */
    public function adminAction(Activity $activity, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UAHGestorActividadesBundle:Enrollment');
        if ($request->query->get('show')) {
            $filter = "enrolled";
        } else {
            $filter = "all";
        }
        $enrollments = $repository->getEnrolledInActivity($activity, $filter);
        $response = $this->render('UAHGestorActividadesBundle:Activity:admin.html.twig', array(
            'enrollments' => $enrollments,
            'activity' => $activity, ));
        $token = $this->get('form.csrf_provider')->generateCsrfToken('administracion');
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @param \Symfony\Component\HttpFoundation\Request    $request
     * @Route("/close/{activity_id}.{_format}", requirements={"activity_id" = "\d+"}, defaults={"activity_id"=-1,"_format"="json"}, options={"expose"=true})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("(is_granted('edit_activity',activity) && is_granted('ROLE_UAH_STAFF_PDI')) || is_granted('ROLE_UAH_ADMIN')")
     */
    public function closeAction(Activity $activity, Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('profile', $request->headers->get('X-CSRFToken'))) {
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
            $activity->setStatus($em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getClosed());
            $em->persist($activity);
            $em->flush();

            return new JsonResponse("OK", 200);
        } else {
            $response = array();
            $response['code'] = self::ERROR_CSRF_TOKEN_INVALID;
            $response['message'] = 'El token CSRF no es válido. Recarga la página e inténtalo de nuevo';
            $response['type'] = 'error';
            $json_response = new JsonResponse($response, 403);

            return $json_response;
        }
    }

    /**
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @param \Symfony\Component\HttpFoundation\Request    $request
     * @Route("/open/{activity_id}.{_format}", requirements={"activity_id" = "\d+"}, defaults={"activity_id"=-1, "_format"="json"}, options={"expose"=true})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     */
    public function openAction(Activity $activity, Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('profile', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();
            $enrollments = $activity->getEnrollees();
            $status_enrolled = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getEnrolledStatus();
            $status_not_recognized = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getNotRecognizedStatus();

            foreach ($enrollments as $enrollment) {
                if ($enrollment->getStatus() === $status_not_recognized) {
                    $enrollment->setStatus($status_enrolled);
                    $em->persist($enrollment);
                }
            }

            $statusActivityRepo = $em->getRepository('UAHGestorActividadesBundle:Statusactivity');

            if (!is_null($activity->getDateApproved())) {
                $activity->setStatus($status);
            } else {
                $status = $statusActivityRepo->getPending();
            }
            $activity->setStatus($status);
            $em->persist($activity);
            $em->flush();

            $response = array();
            $response['code'] = self::ACTIVITY_OPENED;
            $response['message'] = array();
            $response['message']['status'] = $status->getNameEs();
            $response['type'] = 'success';

            return new JsonResponse($response, 200);
        } else {
            $response = array();
            $response['code'] = self::ERROR_CSRF_TOKEN_INVALID;
            $response['message'] = 'El token CSRF no es válido. Recarga la página e inténtalo de nuevo';
            $response['type'] = 'error';
            $json_response = new JsonResponse($response, 403);

            return $json_response;
        }
    }

    /**
     * @Route("/askapproval.{_format}", options={"expose"=true}, defaults={"_format"="json"})
     * @Security("is_granted('ROLE_UAH_STAFF_PDI') || is_granted('ROLE_UAH_ADMIN')")
     */
    public function askApprovalAction(Request $request)
    {
        //Meter protección XSRF
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('profile', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();
            $activityRepository = $em->getRepository('UAHGestorActividadesBundle:Activity');
            $activities = $activityRepository->getActivitiesById(json_decode($request->getContent()));
            $statusPendingApproval = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getPending();
            $statusDraft = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getDraft();
            $response = array();
            $num_activities = 0;
            foreach ($activities as $activity) {
                $statusActivity = $activity->getStatus();
                if ($activity->getOrganizer() !== $this->getUser()) {
                    $response['code'] = self::APPROVAL_ERROR_NOT_ORGANIZER;
                    $response['message'] = 'No eres el organizador de esta actividad:' + $activity->getId();
                    $response['type'] = 'error';

                    return new JsonResponse($response, 400);
                }
                if ($statusActivity === $statusDraft) {
                    $activity->setStatus($statusPendingApproval);
                    $em->persist($activity);
                    $num_activities++;
                } else {
                    $response['code'] = self::APPROVAL_ERROR_INCORRECT_STATUS;
                    $response['message'] = 'Hay un problema con el estado de la actividad:'.$activity->getId();
                    $response['type'] = 'error';

                    return new JsonResponse($response, 400);
                }
            }
            $em->flush();
            $response['code'] = self::APPROVAL_SUCCESSFULLY_ASKED;
            $response['message'] = array();
            $response['message']['num_activities'] = $num_activities;
            $response['message']['status'] = $statusPendingApproval->getNameEs();
            $response['type'] = 'success';

            return new JsonResponse($response, 200);
        } else {
            $response = array();
            $response['code'] = self::ERROR_CSRF_TOKEN_INVALID;
            $response['message'] = 'Problema con la seguridad. Prueba a recargar la página';
            $response['type'] = 'error';

            return new JsonResponse($response, 400);
        }
    }
}
