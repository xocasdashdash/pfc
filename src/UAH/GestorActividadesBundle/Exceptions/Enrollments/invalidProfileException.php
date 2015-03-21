<?php

namespace UAH\GestorActividadesBundle\Exceptions\Enrollments;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class invalidProfileException extends \UAH\GestorActividadesBundle\Exceptions\AbstractException
{

    protected $message = 'Te faltan <a href="profile/update" class="alert-link">completar</a> datos de tu perfil!';
    protected $code = 8;
    protected $type = 'error';

    public function getJSONResponse()
    {
        $response = parent::getJSONResponse();
        $response['type'] = 'error';
        return $response;
    }

    public function getHttpCode()
    {
        return 403;
    }

}
