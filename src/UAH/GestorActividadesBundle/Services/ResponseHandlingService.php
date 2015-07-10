<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Services;

use Psr\Log\LoggerInterface;
use UAH\GestorActividadesBundle\Errors\AbstractError;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of ErrorHandlingService
 *
 * @author xokas
 */
class ResponseHandlingService
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function createJSONResponse($response_data)
    {
        if ($response_data instanceof AbstractError) {
            $this->logger->error($response_data);
            return new JsonResponse($response_data->getJSONResponse(), $response_data->getHttpCode());
        } elseif ($response_data === false) {
            return new JsonResponse('KO', 400);
        } else {
            return new JsonResponse($response_data);
        }
    }
}
