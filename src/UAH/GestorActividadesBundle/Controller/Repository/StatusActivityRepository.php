<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Repository;

class StatusActivityRepository extends EntityRepository {

    /**
     * 
     * @return Esta funcion devuelve el valor por defecto que tienen las actividades.
     */
    public function getDefault(){
        $resultado = $this->getEntityManager->getRepository('UAHGestorActividadesBundle:Statusactivity')->
                        findOneBy(array(
                            'status' => 'STATUS_PENDIENTE'));
        return $resultado;
    }

}
