<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;

class StatusEnrollmentRepository extends EntityRepository {

    /**
     * 
     * @return Esta funcion devuelve el valor por defecto que tienen los enrollment.
     */
    public function getDefault() {
        $resultado = $this->findOneBy(array(
            'status' => 'STATUS_INSCRITO'));
        return $resultado;
    }

    /**
     * 
     * @return Esta funcion devuelve el valor por defecto que tienen los enrollment inactivos.
     */
    public function getInactive() {
        $resultado = $this->findBy(array(
            'status' => 'STATUS_UNENROLLED'));
        return $resultado;
    }

    /**
     * 
     * @return Esta funcion devuelve el valor por defecto que tienen los enrollment inactivos.
     */
    public function getActive() {
        $active_status = array();
        $active_status[] = "STATUS_INSCRITO";
        $active_status[] = "STATUS_RECONOCIDO";
        $active_status[] = "STATUS_VERIFICADO";
        $active_status[] = "STATUS_NO_RECONOCIDO";
        $active_status[] = "STATUS_PENDIENTE_VERIFICACION";
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('st')->from('UAHGestorActividadesBundle:Statusenrollment', 'st')->
                where('st.status in (:status)')->setParameter(':status', $active_status);
        $resultado = $qb->getQuery()->getResult();
        
        return $resultado;
    }

}
