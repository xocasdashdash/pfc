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

class EnrollmentRepository extends EntityRepository {

    /**
     * 
     * @param type $user
     * @param type $activity
     * @return Esta funcion devuelve un resultado verdadero o falso dependiendo de si esta inscrito o no
     */
    public function checkEnrolled($user, $activity) {
        $enrolled_activities = $this->getEnrolledActivitiesId($user);
        return in_array($activity->getId(), $enrolled_activities);
    }
    
    public function canEnroll(Activity $activity){
        $valid_statuses = 
            $this->getEntityManager()->getRepository('UAHGestorActividadesBundle:Statusactivity')->getValidStatus();
        $valid_statuses_ids = \array_map('current',$valid_statuses);
        return \in_array($activity->getStatus()->getId(), $valid_statuses_ids);
        
    }

    /**
     * 
     * @param type $activity
     * @return type Devuelvo el numero de usuarios inscritos teniendo en cuenta los estados
     */
    public function num_enrolled($activity) {
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
     * @param type $user Usuario del que quiero las actividades en las que esta registrado
     * @param type $paginacion Numero de página en la que estoy
     */
    public function getEnrolledActivities($user, $paginacion = 1) {
        $em = $this->getEntityManager();


        $consulta = $em->createQuery('SELECT a.id,a.name,a.englishName,e.dateRegistered,e.isProcessed, e.recognizedCredits,a.start_date,se.status'.
                ' FROM UAHGestorActividadesBundle:Activity a '.
                'JOIN UAHGestorActividadesBundle:Enrollment e' .
                ' WITH a.id = e.activity '.
                'JOIN UAHGestorActividadesBundle:Statusenrollment se'.
                ' with e.status = se.id where e.user=:user and e.status IN (:status)');
        $consulta->setParameter('user', $user);
        $active_status = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getActive();
        $consulta->setParameter('status', $active_status);
        $results = $consulta->getResult(); 
        return $results;
    }
    

}
