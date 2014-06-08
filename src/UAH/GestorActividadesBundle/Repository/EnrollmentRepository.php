<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Repository;
use Doctrine\ORM\EntityRepository;

class EnrollmentRepository extends EntityRepository {

    /**
     * 
     * @param type $user
     * @param type $activity
     * @return Esta funcion devuelve un resultado verdadero o falso dependiendo de si esta inscrito o no
     */
    public function checkEnrolled($user, $activity) {

        $resultado = $this->getEntityManager()->getRepository('UAHGestorActividadesBundle:Enrollment')->
                findBy(array(
            'activity_id' => $activity,
            'user_id' => $user
        ));

        return is_null($resultado);
    }

    /**
     * 
     * @param type $activity
     * @return type Devuelvo el numero de usuarios inscritos teniendo en cuenta los estados
     */
    public function num_enrolled($activity) {
        $em = $this->getENtityManager();
        $consulta = $em->createQuery('SELECT COUNT(e.id) FROM UAHGestorActividadesBundle:Enrollment e where '
                . 'e.activity=:activity and e.status=:status');
        $consulta->setParameter('activity', $activity);
        $default_status = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getDefault();
        $consulta->setParameter('status', $default_status);

        $resultado = $consulta->getSingleScalarResult();
        return $resultado;
    }

}
