<?php

namespace UAH\GestorActividadesBundle\Exceptions\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class applicationNotDeleted extends \Exception
{

    protected $message = 'Justificante NO borrado!';
    protected $code = 4;

}
