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
use UAH\GestorActividadesBundle\Entity\Statusactivity;

class UserRepository extends EntityRepository {

    public function getUserPermits($filter = 'ALL') {
        $em = $this->getEntityManager();

        switch ($filter) {
            case 'STUDENT':
                $role = $em->getRepository('UAHGestorActividadesBundle:Role')->getStudent();
                break;
            case 'STAFF':
                $role = $em->getRepository('UAHGestorActividadesBundle:Role')->getStaffSecretaria();
                break;
            case 'PDI':
                $role = $em->getRepository('UAHGestorActividadesBundle:Role')->getOrganizerPDI();
                break;
            case 'ADMIN':
                $role = $em->getRepository('UAHGestorActividadesBundle:Role')->getAdmin();
                break;
            case 'SUPERADMIN':
                $role = $em->getRepository('UAHGestorActividadesBundle:Role')->getSuperAdmin();
                break;
        }
        if ($filter != 'ALL') {
            $dql = " SELECT dp default_permit, u.id user_id, u.name name, u.apellido_1 apellido1, u.apellido_2 apellido2, u.id_usuldap id_ldap, r.role" .
                    " FROM UAHGestorActividadesBundle:DefaultPermit dp " .
                    " LEFT JOIN UAHGestorActividadesBundle:User u WITH u.id_usuldap = dp.id_usuldap " .
                    " LEFT JOIN dp.roles r" .
                    " WHERE r = :role ";
            $consulta = $em->createQuery($dql);
            $consulta->setParameter('role', $role);
        } else {
            $dql = " SELECT dp default_permit, u.id user_id, u.name name, u.apellido_1 apellido1, u.apellido_2 apellido2, u.id_usuldap id_ldap, r.role" .
                    " FROM UAHGestorActividadesBundle:DefaultPermit dp " .
                    " LEFT JOIN UAHGestorActividadesBundle:User u WITH u.id_usuldap = dp.id_usuldap " .
                    " LEFT JOIN dp.roles r";
            $consulta = $em->createQuery($dql);
        }
        $resultado = $consulta->getResult();
        return $resultado;
    }

    public function getExportData() {
        $em = $this->getEntityManager();
        $dql = " SELECT dp default_permit, r.name " .
                " FROM UAHGestorActividadesBundle:DefaultPermit dp " .
                " LEFT JOIN dp.roles r ";
        $consulta = $em->createQuery($dql);
        $resultado = $consulta->getArrayResult();
        $qb = $em->createQueryBuilder();
        $dql = $qb->select('u', 'r')
                        ->from('UAH\GestorActividadesBundle\Entity\User', 'u')
                        ->leftJoin('u.roles', 'r', \Doctrine\ORM\Query\Expr\Join::WITH)->getDQL();
        return $resultado;
    }

}
