<?php

namespace UAH\GestorActividadesBundle\Errors\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use UAH\GestorActividadesBundle\Errors\AbstractError;

class noEnrollmentsWithThoseIDsError extends AbstractError
{

    protected $message = 'No hay inscripciones con esos id';
    protected $code = 10;
    protected $httpCode = 400;
}
