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

class DegreeRepository extends EntityRepository {

    /**
     * 
     * @param type $user
     * @param type $activity
     * @return Esta funcion devuelve un resultado verdadero o falso dependiendo de si esta inscrito o no
     */
    public function getActive() {
        $em = $this->getEntityManager();
        $active_statuses = $em->getRepository('UAHGestorActividadesBundle:Statusdegree')->getActive();
        $dql = "SELECT d from UAHGestorActividadesBundle:Degree d where d.status in (:active_statuses)";
        $consulta = $em->createQuery($dql);
        $consulta->setParameter('active_statuses', $active_statuses);
        return $consulta->getResult();
    }

}
