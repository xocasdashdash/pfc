<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;

class StatusEnrollmentRepository extends EntityRepository
{
    /**
     *
     * @return Esta funcion devuelve el valor por defecto que tienen los enrollment.
     */
    public function getDefault()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_ENROLLED', ));

        return $resultado;
    }

    /**
     *
     * @return Esta funcion devuelve el valor por defecto que tienen los enrollment inactivos.
     */
    public function getInactive()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_UNENROLLED', ));

        return $resultado;
    }

    /**
     *
     * @return Esta funcion devuelve el valor por defecto que tienen los enrollment inactivos.
     */
    public function getActive()
    {
        $active_status = array();
        $active_status[] = "STATUS_ENROLLED";
        $active_status[] = "STATUS_RECOGNIZED";
        $active_status[] = "STATUS_VERIFIED";
        $active_status[] = "STATUS_NOT_RECOGNIZED";
        $active_status[] = "STATUS_PENDING_VERIFICATION";
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('st')->from('UAHGestorActividadesBundle:Statusenrollment', 'st')->
                where('st.code in (:code)')->setParameter(':code', $active_status);
        $resultado = $qb->getQuery()->getResult();

        return $resultado;
    }

    /**
     *
     * @return Esta funcion devuelve el valor por defecto que tienen los enrollment reconocidos.
     */
    public function getRecognizedStatus()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_RECOGNIZED', ));

        return $resultado;
    }
    /**
     *
     * @return Esta funcion devuelve el valor por defecto que tienen los enrollment reconocidos.
     */
    public function getEnrolledStatus()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_ENROLLED', ));

        return $resultado;
    }
    /**
     *
     * @return Esta funcion devuelve el valor por defecto que tienen los enrollment reconocidos.
     */
    public function getPendingVerificationStatus()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_PENDING_VERIFICATION', ));

        return $resultado;
    }

    /**
     *
     * @return Esta funcion devuelve el valor por defecto que tienen los enrollment reconocidos.
     */
    public function getNotRecognizedStatus()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_NOT_RECOGNIZED', ));

        return $resultado;
    }
    /**
     *
     * @return Esta funcion devuelve el valor por defecto que tienen los enrollment verificados.
     */
    public function getVerified()
    {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_VERIFIED', ));

        return $resultado;
    }
}
