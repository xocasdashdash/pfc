<?php

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;

class StatusActivityRepository extends EntityRepository {

    /**
     * 
     * @return Esta funcion devuelve el valor por defecto que tienen las actividades.
     */
    public function getDefault() {

        return $this->getDraft();
    }

    public function getPending() {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_PENDING'));
        return $resultado;
    }

    public function getDraft() {
        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_DRAFT'));
        return $resultado;
    }

    public function getValidStatus() {

        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_PUBLISHED'));
        return $resultado;
    }

    public function getClosedStatus() {

        $resultado = $this->findOneBy(array(
            'code' => 'STATUS_CLOSED'));
        return $resultado;
    }

}
