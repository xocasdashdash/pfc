<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ActivityRepository extends EntityRepository {

    public function getActivitiesByID($activities) {
        $em = $this->getEntityManager();
        if (count($activities) > 0) {
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
            $dql = " SELECT a " .
                    " FROM UAHGestorActividadesBundle:Activity a " .
                    " WHERE a IN (:activities) " .
                    " ORDER BY a.id ASC";
            $consulta = $em->createQuery($dql);
            $consulta->setParameter('activities', $activities);
            $results = $consulta->getResult();
            return $results;
        } else {
            return null;
        }
    }

}
