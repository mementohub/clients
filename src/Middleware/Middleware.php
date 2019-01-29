<?php

namespace iMemento\Clients\Middleware;

use GuzzleHttp\Middleware as GuzzleMiddleware;
use Psr\Http\Message\ResponseInterface;
use iMemento\Clients\Responses\JsonResponse;
use iMemento\Clients\Responses\CollectionResponse;

class Middleware
{
    public static function collection()
    {
        return GuzzleMiddleware::mapResponse(
            function (ResponseInterface $response) {
                return new CollectionResponse($response);
            }
        );
    }

    public static function json()
    {
        return GuzzleMiddleware::mapResponse(
            function (ResponseInterface $response) {
                return new JsonResponse($response);
            }
        );
    }

    public static function retries(int $allowed)
    {
        return GuzzleMiddleware::retry(
            function ($retries, $request, ResponseInterface $response) use ($allowed) {
                return (($response->getStatusCode() >= 400) 
                    && ($retries < $allowed));
            }
        );
    }

    public static function auth($config, $default)
    {
        $middleware = new AuthMiddleware($config, $default);
        return $middleware->handler();
    }

    public static function errors($config, $default)
    {
        $middleware = new ErrorMiddleware($config, $default);
        return $middleware->handler();
    }
}
