<?php

namespace UAH\GestorActividadesBundle\Errors\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use UAH\GestorActividadesBundle\Errors\AbstractError;

class enrollmentNotRecognizedError extends AbstractError
{

    protected $message = 'El estado no es el correcto';
    protected $code = 2;
    protected $httpCode = 400;
}
