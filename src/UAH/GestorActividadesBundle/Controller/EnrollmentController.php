<?php

namespace UAH\GestorActividadesBundle\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use UAH\GestorActividadesBundle\Entity\Enrollment;
use UAH\GestorActividadesBundle\Entity\Activity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UAH\GestorActividadesBundle\Repository\EnrollmentRepository;

class EnrollmentController extends Controller {

    const ENROLLMENT_OK = 0;
    //Error cuando ya estoy inscrito en la actividad
    const ENROLLMENT_ERROR_ALREADY_ENROLLED = 1;
    //Error cuando no me puedo inscribir en la actividad por que no hay más plazas
    const ENROLLMENT_ERROR_NO_PLACES = 2;
    //Error cuando no me puedo inscribir en la actividad por su estado
    const ENROLLMENT_ERROR_INVALID_ACTIVITY = 4;
    const ENROLLMENT_ERROR_UNKNOWN = 8;

    /**
     * @Route("/enroll/{activity_id}",requirements={"pagina" = "\d+"}, options={"expose"=true})
     * @ParamConverter("activity", class="UAHGestorActividadesBundle:Activity",options={"id" = "activity_id"})
     * @Security("is_granted('ROLE_UAH_STUDENT')")
     */
    public function enrollAction(Activity $activity) {
        /*
         * 
         * Inscribo al usuario
         */
        //Cargo el usuario
        $user = $this->getUser();
        //Compruebo que la actividad tiene huecos libres (places_occupied<places_offered)
        //Compruebo que el usuario no esta ya inscrito en la actividad (Si ya esta, le devuelvo ok)
        $em = $this->getDoctrine()->getManager();
        //Uso bitmasks para saber que tipo de error hay (si lo hay) 
        $check_enrolled = $em->getRepository('UAHGestorActividadesBundle:Enrollment')->checkEnrolled($user, $activity);
        $free_places = ($activity->getNumberOfPlacesOccupied() >= $activity->getNumberOfPlacesOffered()) << 1;
        $can_enroll =  ($em->getRepository('UAHGestorActividadesBundle:Enrollment')->canEnroll($activity));
        $permissions = $check_enrolled | $free_places <<1 | $can_enroll <<2;
        $response = array();

        if ($permissions & self::ENROLLMENT_ERROR_ALREADY_ENROLLED) {
            $response['type'] = 'notice';
            $response['code'] = self::ENROLLMENT_ERROR_ALREADY_ENROLLED;
            $response['message'] = 'Ya estás inscrito!';
            $code = 403;
        } else if ($permissions & self::ENROLLMENT_ERROR_NO_PLACES) {
            $response['type'] = 'notice';
            $response['code'] = self::ENROLLMENT_ERROR_NO_PLACES;
            $response['message'] = 'No hay plazas libres.';
            $code = 403;
        } else if ($permissions & self::ENROLLMENT_ERROR_INVALID_ACTIVITY) {
            $response['type'] = 'error';
            $response['code'] = self::ENROLLMENT_ERROR_INVALID_ACTIVITY;
            $response['message'] = 'Actividad no disponible.';
            $code = 403;
        } else {
            $enrollment = new Enrollment();
            $enrollment->setActivity($activity);
            $enrollment->setUser($user);
            $em->persist($enrollment);
            $activity->setNumberOfPlacesOccupied($activity->getNumberOfPlacesOccupied() + 1);
            $em->persist($activity);
            $em->flush();
            $code = 200;
            $response = 'Enrolled';
        }
        return new JsonResponse($response, $code);
    }

}
