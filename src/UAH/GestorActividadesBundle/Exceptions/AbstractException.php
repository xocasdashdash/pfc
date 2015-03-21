<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Exceptions;

/**
 * Description of AbstractException
 *
 * @author xokas
 */
abstract class AbstractException extends \Exception
{

    public function getJSONResponse()
    {
        $response = array();
        $response['code'] = $this->getCode();
        $response['message'] = $this->getMessage();
        return $response;
    }

    abstract function getHttpCode();

}
