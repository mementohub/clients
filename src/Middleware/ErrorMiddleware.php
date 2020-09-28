<?php

namespace iMemento\Clients\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class ErrorMiddleware
{
    protected $nextHandler;
    protected $mode;

    public function __construct(callable $nextHandler, string $mode = 'critical')
    {
        $this->nextHandler = $nextHandler;
        $this->mode        = $mode;
    }

    protected function shouldFail()
    {
        return ($this->mode != 'silent');
    }

    public function __invoke(RequestInterface $request, array $options)
    {
        $fn = $this->nextHandler;

        if ($this->shouldFail()) {
            // the default behaviour
            return $fn($request, $options);
        }

        $options['http_errors'] = false;
        return $fn($request, $options)->then(
            function (ResponseInterface $response) use ($request, $fn) {
                $code = $response->getStatusCode();
                if ($code < 400) {
                    return $response;
                }
                $exception = RequestException::create($request, $response);
                logger()->debug($exception->getMessage(), $exception->getTrace());
                return $response;
            }
        );
    }
}
