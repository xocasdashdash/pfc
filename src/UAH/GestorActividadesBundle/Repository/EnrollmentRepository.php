<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use UAH\GestorActividadesBundle\Entity\Activity;
use UAH\GestorActividadesBundle\Entity\User;

class EnrollmentRepository extends EntityRepository {

    /**
     * 
     * @param type $user
     * @param type $activity
     * @return Esta funcion devuelve un resultado verdadero o falso dependiendo de si esta inscrito o no
     */
    public function checkEnrolled(User $user, Activity $activity) {
        $enrolled_activities = $this->getEnrolledActivitiesId($user);
        return in_array($activity->getId(), $enrolled_activities);
    }

    /**
     * 
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @return boolean si puede o no inscribirse según el estado de la actividad
     */
    public function canEnroll(Activity $activity) {
        $valid_status = $this->getEntityManager()->getRepository('UAHGestorActividadesBundle:Statusactivity')->getValidStatus();
        return $activity->getStatus() === $valid_status;
    }

    /**
     * 
     * @param type $activity
     * @return type Devuelvo el numero de usuarios inscritos teniendo en cuenta los estados
     */
    public function num_enrolled(Activity $activity) {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT COUNT(e.id) FROM UAHGestorActividadesBundle:Enrollment e where '
                . 'e.activity=:activity and e.status=:status');
        $consulta->setParameter('activity', $activity);
        $default_status = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getDefault();
        $consulta->setParameter('status', $default_status);

        $resultado = $consulta->getSingleScalarResult();
        return $resultado;
    }

    /**
     * 
     * @param type $user Numero de página en la que estoy
     * @param type $paginacion Usuario del que quiero las actividades en las que esta registrado
     */
    public function getEnrolledActivitiesId($user, $paginacion = 1) {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT a.id FROM UAHGestorActividadesBundle:Activity a JOIN UAHGestorActividadesBundle:Enrollment e' .
                ' WITH a.id = e.activity where e.user=:user and e.status IN (:status)');
        $consulta->setParameter('user', $user);
        $active_status = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getActive();
        $consulta->setParameter('status', $active_status);
        $results = $consulta->getResult();
        return array_map('current', $results);
    }

    /**
     * 
     * @param User $user Usuario del que quiero las actividades en las que esta registrado
     * @param int $paginacion Numero de página en la que estoy
     */
    public function getEnrolledActivities(User $user, $paginacion = 1) {
        $em = $this->getEntityManager();


        $consulta = $em->createQuery('SELECT a.id,e.id as enrollment_id, a.name, a.englishName, e.dateRegistered, ' .
                ' e.recognizedCredits,a.start_date,se.code, e.id as id_enrollment, ' .
                ' IDENTITY(e.application) as application' .
                ' FROM UAHGestorActividadesBundle:Activity a ' .
                'JOIN UAHGestorActividadesBundle:Enrollment e' .
                ' WITH a.id = e.activity ' .
                'JOIN UAHGestorActividadesBundle:Statusenrollment se' .
                ' with e.status = se.id where e.user=:user and e.status IN (:status)');
        $consulta->setParameter('user', $user);
        $active_status = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getActive();
        $consulta->setParameter('status', $active_status);
        $results = $consulta->getResult();
        return $results;
    }

    /**
     * 
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity Actividad que voy a cargar
     * @param String $filter Filtro que voy a usar
     */
    public function getEnrolledInActivity(Activity $activity, $filter = "all") {
        $em = $this->getEntityManager();
        $dql = " SELECT u.name,u.apellido_1,u.apellido_2 ,u.email,e.dateRegistered, e.id," .
                " se.code as status_enrollment, e.recognizedCredits, IDENTITY(u.degree_id) as degree_id, " .
                " sd.code as status_degree " .
                " FROM UAHGestorActividadesBundle:Enrollment e " .
                " JOIN UAHGestorActividadesBundle:User u " .
                " WITH u.id = e.user " .
                " JOIN UAHGestorActividadesBundle:Statusenrollment se " .
                " WITH e.status = se.id " .
                " JOIN UAHGestorActividadesBundle:Degree d " .
                " WITH d.id = u.degree_id " .
                " JOIN UAHGestorActividadesBundle:Statusdegree sd " .
                " WITH d.status = sd.id " .
                " WHERE e.activity = :activity ";
        if ($filter !== "all") {
            $dql .= " and e.status = :status ORDER BY e.dateRegistered DESC";
            if ($filter === "enrolled") {
                $status = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getEnrolledStatus();
            } else if ($filter === "recognized") {
                $status = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getRecognizedStatus();
            } else if ($filter === "not_recognized") {
                $status = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getNotRecognizedStatus();
            }
        } else {
            $dql .= " ORDER BY e.dateRegistered DESC ";
        }
        $consulta = $em->createQuery($dql);
        if ($filter !== "all") {
            $consulta->setParameter('status', $status);
        }
        $consulta->setParameter('activity', $activity);
        $results = $consulta->getResult();
        return $results;
    }

    /**
     * 
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity Actividad que voy a cargar
     */
    /*
      SELECT e.id, e.activity, se.status as status_enrollment, sd.status as status_degree FROM UAHGestorActividadesBundle:Enrollment e JOIN UAHGestorActividadesBundle:User u WITH u.id = e.user JOIN UAHGestorActividadesBundle:Statusenrollment se WITH e.status = se.id JOIN UAHGestorActividadesBundle:Degree d WITH d.id = u.degree_id JOIN UAHGestorActividadesBundle:Statusdegree sd WITH d.status = sd.id JOIN UAHGestorActividadesBundle:Activity a WITH e.activity = a.id WHERE e.enrollment.id IN (:enrollments)
     */
    public function getEnrollmentsByID($enrollments) {
        $em = $this->getEntityManager();
        if (count($enrollments) > 0) {
//        $dql = " SELECT e.id, a, " .
//                " se.code as status_enrollment, " .
//                " sd.code as status_degree " .
//                " FROM UAHGestorActividadesBundle:Enrollment e " .
//                " JOIN UAHGestorActividadesBundle:User u " .
//                " WITH u.id = e.user " .
//                " JOIN UAHGestorActividadesBundle:Statusenrollment se " .
//                " WITH e.status = se.id " .
//                " JOIN UAHGestorActividadesBundle:Degree d " .
//                " WITH d.id = u.degree_id " .
//                " JOIN UAHGestorActividadesBundle:Statusdegree sd " .
//                " WITH d.status = sd.id " .
//                " JOIN UAHGestorActividadesBundle:Activity a " .
//                " WITH e.activity = a.id " .
//                " WHERE e IN (:enrollments) " .
//                " ORDER BY e.id ASC";
            $dql = " SELECT e " .
                    " FROM UAHGestorActividadesBundle:Enrollment e " .
                    " WHERE e IN (:enrollments) " .
                    " ORDER BY e.id ASC";
            $consulta = $em->createQuery($dql);
            $consulta->setParameter('enrollments', $enrollments);
            $results = $consulta->getResult();
            return $results;
        } else {
            return null;
        }
    }

}
