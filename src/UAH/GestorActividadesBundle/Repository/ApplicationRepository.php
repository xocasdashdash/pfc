<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use UAH\GestorActividadesBundle\Entity\User;

class ApplicationRepository extends EntityRepository {

    public function getUserApplications(User $user, $filter = 'all') {
        $em = $this->getEntityManager();
        if ($filter === 'not_archived') {
            $archived_status = $em->getRepository('UAHGestorActividadesBundle:Statusapplication')->getArchived();
            $dql = "SELECT a FROM UAHGestorActividadesBundle:Application a where a.user =:user and a.status != :status ORDER BY a.applicationDateCreated DESC";
            $consulta = $em->createQuery($dql);
            $consulta->setParameter('status', $archived_status);
        } elseif ($filter === 'all') {
            $dql = "SELECT a FROM UAHGestorActividadesBundle:Application a where a.user =:user ORDER BY a.applicationDateCreated DESC";
            $consulta = $em->createQuery($dql);
        }
        $consulta->setParameter('user', $user);
        $result = $consulta->getResult();
        return $result;
    }

}
