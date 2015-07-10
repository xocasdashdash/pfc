<?php

namespace UAH\GestorActividadesBundle\Errors\Enrollments;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class invalidActivityError extends \UAH\GestorActividadesBundle\Errors\AbstractError
{
    protected $message = 'Actividad no disponible';
    protected $code = 4;
    protected $type = 'error';
    protected $httpCode = 403;

    public function getJSONResponse()
    {
        $response = parent::getJSONResponse();
        $response['type'] = $this->type;
        return $response;
    }
}
