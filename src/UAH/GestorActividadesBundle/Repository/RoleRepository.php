<?php

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository
{
    public function getAdmin()
    {
        return $this->findRole('ROLE_UAH_ADMIN');
    }

    public function getSuperAdmin()
    {
        return $this->findRole('ROLE_UAH_SUPER_ADMIN');
    }

    public function getOrganizerPDI()
    {
        return $this->findRole('ROLE_UAH_STAFF_PDI');
    }

    public function getStaffSecretaria()
    {
        return $this->findRole('ROLE_UAH_STAFF_PAS');
    }

    public function getStudent()
    {
        return $this->findRole('ROLE_UAH_STUDENT');
    }

    private function findRole($role)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT r FROM UAHGestorActividadesBundle:Role r ".
                "WHERE r.role = :role";
        $consulta = $em->createQuery($dql);
        $consulta->setParameter('role', $role);
        $resultado = $consulta->getSingleResult();

        return $resultado;
    }
}
