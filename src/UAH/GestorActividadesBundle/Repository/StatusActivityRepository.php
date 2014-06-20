<?php

namespace UAH\GestorActividadesBundle\Repository;
use Doctrine\ORM\EntityRepository;

class StatusActivityRepository extends EntityRepository {

    /**
     * 
     * @return Esta funcion devuelve el valor por defecto que tienen las actividades.
     */
    public function getDefault(){
        $resultado = $this->findOneBy(array(
                            'status' => 'STATUS_PENDIENTE'));
        return $resultado;
    }

    public function getValidStatus(){
        $valid_status = array();        
        $valid_status[] = "STATUS_PUBLICADO";
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('st')->from('UAHGestorActividadesBundle:Statusactivity', 'st')->
                where('st.status in (:status)')->setParameter(':status', $valid_status);
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}
