<?php

namespace UAH\GestorActividadesBundle\Errors\Enrollments;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class invalidRecognizementError extends \UAH\GestorActividadesBundle\Errors\AbstractError
{
    protected $message = 'Estado de inscripción no válido';
    protected $code = 2;
    protected $type = 'error';
    protected $httpCode = 400;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function getJSONResponse()
    {
        $response = parent::getJSONResponse();
        $response['type'] = $this->type;
        return $response;
    }
}
