<?php

namespace UAH\GestorActividadesBundle\Exceptions\Enrollments;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class invalidRecognizementException extends \UAH\GestorActividadesBundle\Exceptions\AbstractException
{

    protected $message = 'Estado de inscripción no válido';
    protected $code = 2;
    protected $type = 'error';

    public function __construct($message, $code, $previous)
    {
        parent::__construct($message, null, null);
    }

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
