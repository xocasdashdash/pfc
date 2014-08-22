<?php

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository {

    public function getAll() {
        $em = $this->getEntityManager();
        $dql = "SELECT c obj,s.code tipo from UAHGestorActividadesBundle:Category c LEFT JOIN c.status s";
        $consulta = $em->createQuery($dql);
        return $consulta->getResult();
    }

    public function getActive($type = 'obj') {
        return $this->getByStatus('active', $type);
    }

    public function getInactive($type = 'obj') {
        return $this->getByStatus('inactive', $type);
    }

    private function getByStatus($status, $type) {
        $em = $this->getEntityManager();

        if ($status === 'active') {
            $status = $em->getRepository('UAHGestorActividadesBundle:Statuscategory')->getActive();
        } elseif ($status === 'inactive') {
            $status = $em->getRepository('UAHGestorActividadesBundle:Statuscategory')->getInactive();
        } else {
            return $this->getAll();
        }
        $dql = "SELECT c from UAHGestorActividadesBundle:Category c WHERE c.status = :status";
        //        $dql = "SELECT c obj, count(a) from UAHGestorActividadesBundle:Category c LEFT JOIN c.activities a WHERE c.status = :status GROUP BY a.categories";

        $consulta = $em->createQuery($dql);
        $consulta->setParameter('status', $status);
        if ($type === 'obj') {
            return $consulta->getResult();
        } elseif ($type === 'arr') {
            return $consulta->getArrayResult();
        }
    }

    public function getParentCategories() {
        $em = $this->getEntityManager();
        $dql = "SELECT c from UAHGestorActividadesBundle:Category c LEFT JOIN c.status s WHERE c.status = :active_status and c.parent_category is NULL";
        $active_status = $em->getRepository('UAHGestorActividadesBundle:Statuscategory')->getActive();

        $consulta = $em->createQuery($dql);
        $consulta->setParameter('active_status', $active_status);
        return $consulta->getResult();
    }

}
