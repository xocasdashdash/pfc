<?php

namespace UAH\GestorActividadesBundle\Errors\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use UAH\GestorActividadesBundle\Errors\AbstractError;

class applicationMixedTypeOfCreditsError extends AbstractError
{

    protected $message = 'Hay una mezcla entre créditos de libre y créditos ECTS. \n Ponte en contacto con el administrador';
    protected $code = 9;
    protected $httpCode = 400;

}
