<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Repository;

/**
 * Description of DefaultPermitRepository
 *
 * @author xokas
 */
class DefaultPermitRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllSuperAdminDefaultPermits(){
        $dql = "select dp, r ".
                " FROM UAHGestorActividadesBundle:DefaultPermits dp".
                " JOIN dp.roles r WITH r.role = 'ROLE_UAH_SUPER_ADMIN";
        
        $query = $this->getEntityManager()->createQuery($dql);
        $result = $query->getResult();
        return $result;
        
    }
}