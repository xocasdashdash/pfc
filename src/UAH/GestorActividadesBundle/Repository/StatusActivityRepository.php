<?php

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Criteria;

class StatusActivityRepository extends EntityRepository
{
    /**
     *
     * @return Esta funcion devuelve el valor por defecto que tienen las actividades.
     */
    public function getDefault()
    {
        return $this->getDraft();
    }

    public function getPending()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_PENDING', ));

        return $resultado;
    }

    public function getEditableStatus()
    {
        $qb = $this->createQueryBuilder('editableStatus');
        $criteria = Criteria::create();
        $editableStatus = array('STATUS_DRAFT');
        $criteria->orWhere(Criteria::expr()->in('code', $editableStatus));
        $qb->addCriteria($criteria);
        $res = $qb->getQuery()->getResult();
        return $res;
    }

    public function getDraft()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_DRAFT', ));

        return $resultado;
    }

    public function getValidStatus()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_PUBLISHED', ));

        return $resultado;
    }

    public function getClosed()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_CLOSED', ));

        return $resultado;
    }

    public function getApproved()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_APPROVED', ));

        return $resultado;
    }

    public function getPublished()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_PUBLISHED', ));

        return $resultado;
    }
}
