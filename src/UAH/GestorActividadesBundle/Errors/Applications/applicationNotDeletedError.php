<?php

namespace UAH\GestorActividadesBundle\Errors\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use UAH\GestorActividadesBundle\Errors\AbstractError;

class applicationNotDeletedError extends AbstractError
{
    protected $message = 'Justificante NO borrado!';
    protected $code = 4;
    protected $httpCode = 400;
}
