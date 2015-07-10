<?php

namespace UAH\GestorActividadesBundle\Errors\Enrollments;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class numberOfCreditsOutOFRangeError extends \UAH\GestorActividadesBundle\Errors\AbstractError
{
    protected $message = 'Número de créditos fuera de rango';
    protected $code = 2;
    protected $type = 'error';
    protected $httpCode = 403;

    public function getJSONResponse()
    {
        $response = parent::getJSONResponse();
        $response['type'] = $this->type;
        return $response;
    }
}
