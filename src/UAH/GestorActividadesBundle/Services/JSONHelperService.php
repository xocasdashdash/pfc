<?php

namespace UAH\GestorActividadesBundle\Services;

/**
 * Description of JSONHelperService
 *
 * @author xokas
 */
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\DependencyInjection\Container;

class JSONHelperService
{

    const ERROR_CSRF_TOKEN_INVALID = -3;

    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function generateResponseFromException(\Exception $ex)
    {
        $response = array();
        $response['message'] = $ex->getMessage();
        $response['code'] = $ex->getCode();
        return new JsonResponse($response, 400);
    }

    public function generateInvalidCSRFTokenResponse($intention)
    {
        $response = array();
        /* @var $formCSRFProvider \Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfTokenManagerAdapter */
        $formCSRFProvider = $this->container->get('form.csrf_provider');
        $token = $formCSRFProvider->generateCsrfToken($intention);
        $response['code'] = self::ERROR_CSRF_TOKEN_INVALID;
        $response['message'] = 'El token CSRF no es válido. Intentalo de nuevo';
        $response['type'] = 'error';
        $jsonResponse = new JsonResponse($response, 403);
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $jsonResponse->headers->setCookie($cookie);
        return $jsonResponse;
    }

}
