<?php

namespace UAH\GestorActividadesBundle\Services;

/**
 * Description of JSONHelperService
 *
 * @author xokas
 */
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class JSONHelperService
{
    const ERROR_CSRF_TOKEN_INVALID = -3;

    protected $csrf_provider;

    public function setCSRFProvider(CsrfTokenManager $csrf_provider)
    {
        $this->csrf_provider = $csrf_provider;
    }

    /**
     *
     * @return CsrfTokenManagerInterface
     */
    public function getCSRFProvider()
    {
        return $this->csrf_provider;
    }

    public function generateResponseFromException(\Exception $ex)
    {
        if (is_subclass_of($ex, '\UAH\GestorActividadesBundle\Exceptions\AbstractException')) {
            return JsonResponse($ex->getJSONResponse(), $ex->getHttpCode());
        }
        $response = array();
        $response['message'] = $ex->getMessage();
        $response['code'] = $ex->getCode();
        return new JsonResponse($response, 400);
    }

    public function generateInvalidCSRFTokenResponse($intention)
    {
        $response = array();
        $token = $this->getCSRFProvider()->getToken($intention);
        $response['code'] = self::ERROR_CSRF_TOKEN_INVALID;
        $response['message'] = 'El token CSRF no es válido. Intentalo de nuevo';
        $response['type'] = 'error';
        $jsonResponse = new JsonResponse($response, 403);
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $jsonResponse->headers->setCookie($cookie);
        return $jsonResponse;
    }
}
