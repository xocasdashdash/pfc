<?php

namespace UAH\GestorActividadesBundle\Errors\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use UAH\GestorActividadesBundle\Errors\AbstractError;

class notYourApplicationError extends AbstractError
{

    protected $message = 'No es el tuyo';
    protected $code = 1;
    protected $httpCode = 400;

}
