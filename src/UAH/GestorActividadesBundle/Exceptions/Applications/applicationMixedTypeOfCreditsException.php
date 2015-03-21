<?php

namespace UAH\GestorActividadesBundle\Exceptions\Applications;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class applicationMixedTypeOfCredits extends \Exception
{

    protected $message = 'Hay una mezcla entre créditos de libre y créditos ECTS. \n Ponte en contacto con el administrador';
    protected $code = 9;
}
