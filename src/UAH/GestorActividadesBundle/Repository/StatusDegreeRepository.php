<?php

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * StatusDegreeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StatusDegreeRepository extends EntityRepository
{
    /**
     *
     * @return \UAH\GestorActividadesBundle\Entity\Statusdegree[]
     */
    public function getActive()
    {
        $dql = "SELECT sd from UAHGestorActividadesBundle:Statusdegree sd where sd.code = 'STATUS_RENEWED' or sd.code ='STATUS_NON_RENEWED'";
        $consulta = $this->getEntityManager()->createQuery($dql);

        return $consulta->getResult();
    }

    /**
     *
     * @return \UAH\GestorActividadesBundle\Entity\Statusdegree
     */
    public function getRenewed()
    {
        return $this->getByStatus('STATUS_RENEWED');
    }

    /**
     *
     * @return \UAH\GestorActividadesBundle\Entity\Statusdegree
     */
    public function getNotRenewed()
    {
        return $this->getByStatus('STATUS_NON_RENEWED');
    }

    /**
     *
     * @return \UAH\GestorActividadesBundle\Entity\Statusdegree
     */
    public function getInactive()
    {
        return $this->getByStatus('STATUS_INACTIVE');
    }

    /**
     *
     * @return \UAH\GestorActividadesBundle\Entity\Statusdegree
     */
    private function getByStatus($status)
    {
        $dql = "SELECT sd from UAHGestorActividadesBundle:Statusdegree sd where sd.code = :code";
        $consulta = $this->getEntityManager()->createQuery($dql);
        $consulta->setParameter('code', $status);

        return $consulta->getSingleResult();
    }
}
