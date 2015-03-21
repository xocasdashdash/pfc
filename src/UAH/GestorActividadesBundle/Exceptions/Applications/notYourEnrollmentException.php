<?php

namespace UAH\GestorActividadesBundle\Exceptions\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class notYourEnrollmentException extends \Exception
{

    protected $message = 'No es el tuyo';
    protected $code = 1;

}
