<?php

namespace UAH\GestorActividadesBundle\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use UAH\GestorActividadesBundle\Entity\Enrollment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class EnrollmentController extends Controller {

    /**
     * @Route("/enroll/{activity_id}",requirements={"pagina" = "\d+"})
     * @ParamConverter("post", class="UAHGestorACtividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("has_role('UAH_ROLE_STUDENT')")
     */
    public function enrollAction(Activity $actividad) {
        /*
         * 
         * Inscribo al usuario
         */
        //Cargo el usuario
        $user = $this->getUser();
        //Compruebo que la actividad tiene huecos libres (places_occupied<places_offered)
        if ($actividad->getNumberOfPlacesOccupied() < $actividad->getNumberOfPlacesOffered()) {
            //Compruebo que el usuario no esta ya inscrito en la actividad (Si ya esta, le devuelvo ok)
            $em = $this->getDoctrine()->getManager();
            $is_enrolled = $em->getRepository('UAHGestorActividadesBundle:Enrollment')->checkEnrolled($user, $actividad);
            if ($is_enrolled) {
                return new Response('Enrolled');
            } else {
                $enrollment = new Enrollment();
                $enrollment->setActividad($actividad);
                $enrollment->setUser($user);
                $em->persist($enrollment);
                $actividad->setNumberOfPlacesOccupied;
            }
        }
    }

}
