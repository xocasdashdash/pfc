<?php

namespace UAH\GestorActividadesBundle\Exceptions\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class enrollmentAlreadyUsed extends \Exception
{

    protected $message = 'Alguna de las inscripciones esta ya en otro justificante';
    protected $code = 6;

}
