<?php

namespace UAH\GestorActividadesBundle\Errors\Enrollments;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class wrongNumberFormatError extends \UAH\GestorActividadesBundle\Errors\AbstractError
{
    protected $message = 'Formato de número incorrecto';
    protected $code = 2;
    protected $type = 'error';
    protected $httpCode = 400;

    public function getJSONResponse()
    {
        $response = parent::getJSONResponse();
        $response['type'] = $this->type;
        return $response;
    }
}
