<?php

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * StatusDegreeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StatusApplicationRepository extends EntityRepository
{
    public function getDefault()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_CREATED', ));

        return $resultado;
    }

    public function getVerified()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_VERIFIED', ));

        return $resultado;
    }

    public function getArchived()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_ARCHIVED', ));

        return $resultado;
    }
}
