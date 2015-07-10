<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Errors;

/**
 * Description of AbstractException
 *
 * @author xokas
 */
abstract class AbstractError
{
    protected $httpCode = 400;
    protected $code = -1;
    protected $message = 'Error';

    public function getJSONResponse()
    {
        $response = array();
        $response['code'] = $this->getCode();
        $response['message'] = $this->getMessage();
        return $response;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }

    public function __toString()
    {
        $fecha = new \DateTime();
        $stringMessage = is_array($this->message) ? implode(';', $this->message) : $this->message;
        $respuesta = "[{$fecha->format('c')}] Error. Codigo: {$this->code}| Mensaje: {$stringMessage} | Codigo Http: {$this->httpCode}";
        return $respuesta;
    }
}
