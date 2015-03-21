<?php

namespace UAH\GestorActividadesBundle\Exceptions\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class noEnrollmentsWithThoseIDsException extends \Exception
{

    protected $message = 'No hay inscripciones con esos id';
    protected $code = 10;

}
