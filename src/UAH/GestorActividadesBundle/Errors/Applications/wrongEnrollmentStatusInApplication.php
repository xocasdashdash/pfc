<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Errors\Applications;

/**
 * Description of wrongEnrollmentStatusInApplication
 *
 * @author xokas
 */
class wrongEnrollmentStatusInApplication extends \UAH\GestorActividadesBundle\Errors\AbstractError
{
    protected $message = 'Uno de los estados de la application no es el correcto';
    protected $code = 10;
    protected $httpCode = 400;
}
