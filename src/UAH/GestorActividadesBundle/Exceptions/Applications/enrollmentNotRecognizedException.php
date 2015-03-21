<?php

namespace UAH\GestorActividadesBundle\Exceptions\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class enrollmentNotRecognizedException extends \Exception
{

    protected $message = 'El estado no es el correcto';
    protected $code = 2;

}
