<?php

namespace UAH\GestorActividadesBundle\Exceptions\Enrollments;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class wrongActivityException extends \UAH\GestorActividadesBundle\Exceptions\AbstractException
{

    protected $message = 'La actividad no se corresponde';
    protected $code = 2;
    protected $type = 'error';

    public function getJSONResponse()
    {
        $response = parent::getJSONResponse();
        $response['type'] = $this->type;
        return $response;
    }

    public function getHttpCode()
    {
        return 400;
    }

}
