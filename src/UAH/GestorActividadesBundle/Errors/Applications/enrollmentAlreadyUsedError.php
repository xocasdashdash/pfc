<?php

namespace UAH\GestorActividadesBundle\Errors\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use UAH\GestorActividadesBundle\Errors\AbstractError;

class enrollmentAlreadyUsedError extends AbstractError
{
    protected $message = 'Alguna de las inscripciones esta ya en otro justificante';
    protected $code = 6;
    protected $httpCode = 400;
}
