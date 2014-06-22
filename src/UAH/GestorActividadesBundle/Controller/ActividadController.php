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

class ActividadController extends Controller {

    /**
     * @Route("/actividad/{activity_id}/{slug}",requirements={"activity_id" = "\d+"}, options={"expose"=true}))
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Method({"GET"})
     */
    public function indexAction(Activity $activity, Request $request) {
        if ($request->isXmlHttpRequest()) {
            return new JSONResponse($activity);
        } else {
            return $this->render('UAHGestorActividadesBundle:Actividad:index.html.twig', array(
                        'activity' => $activity
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
            $activity->setIsPublic(true);
            $activity->setNumberOfPlacesOccupied(0);
            $activity->setApprovedByComitee(0);
            $activity->setIsActive(true);
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
     * @Security("has_role('ROLE_UAH_STAFF_PDI')")
     */
    public function editAction(Activity $activity, Request $request) {
        return $this->render('UAHGestorActividadesBundle:Actividad:edit.html.twig', array(
                    'form' => $form->createView()));
    }

    /**
     * @Route("/actividad/admin/{activity_id}", requirements={"activity_id" = "\d+"}, defaults={"activity_id"=-1})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("has_role('ROLE_UAH_STAFF_PDI')")
     */
    public function adminAction(Activity $activity, Request $request) {
        return $this->render('UAHGestorActividadesBundle:Actividad:admin.html.twig', array(
                    'form' => $form->createView()));
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

}
