<?php

namespace iMemento\Clients\Middleware;

use GuzzleHttp\Middleware as GuzzleMiddleware;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use iMemento\Clients\Responses\JsonResponse;
use iMemento\Clients\Responses\CollectionResponse;

class Middleware
{
    public static function collection(string $mode)
    {
        return GuzzleMiddleware::mapResponse(
            function (ResponseInterface $response) use ($mode) {
                try {
                    return new CollectionResponse($response);
                } catch (\Exception $e) {
                    if ($mode != 'silent') {
                        throw $e;
                    }
                    return new CollectionResponse(new Response(444, [], '{}', null, $e->getMessage()));
                }
            }
        );
    }

    public static function json(string $mode)
    {
        return GuzzleMiddleware::mapResponse(
            function (ResponseInterface $response) use ($mode) {
                try {
                    return new JsonResponse($response);
                } catch (\Exception $e) {
                    if ($mode != 'silent') {
                        throw $e;
                    }
                    return new JsonResponse(new Response(444, [], '{}', null, $e->getMessage()));
                }
            }
        );
    }

    public static function retries(int $allowed, callable $delay = null)
    {
        return GuzzleMiddleware::retry(
            function ($retries, $request, ResponseInterface $response) use ($allowed) {
                return (($response->getStatusCode() >= 400)
                    && ($retries < $allowed));
            },
            $delay
        );
    }

    public static function auth(string $method, $token = null)
    {
        $middleware = new AuthMiddleware($method, $token);
        return $middleware->handler();
    }

    public static function errors(string $mode)
    {
        $middleware = new ErrorMiddleware($mode);
        return $middleware->handler();
    }
}
