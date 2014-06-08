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

}
