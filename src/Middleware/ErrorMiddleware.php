<?php

namespace iMemento\Clients\Middleware;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;

class ErrorMiddleware
{
    protected $config = [];
    protected $default = '';

    public function __construct($config, $default)
    {
        $this->config = $config;
        $this->default = $default;
    }

    protected function mode()
    {
        return $this->config['mode']['requested']
            ?? $this->config['mode']['preferred']
            ?? $this->default;
    }

    protected function shouldFail()
    {
        return ($this->mode() !== 'silent');
    }

    public function handler()
    {
        return function (callable $handler) {
            return function ($request, array $options) use ($handler) {
                if ($this->shouldFail()) {
                    // the default behaviour
                    return $handler($request, $options);
                }
                
                $options['http_errors'] = false;
                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($request, $handler) {
                        $code = $response->getStatusCode();
                        if ($code < 400) {
                            return $response;
                        }
                        $exception = RequestException::create($request, $response);
                        logger()->debug($exception->getMessage(), $exception->getTrace());
                        return $response;
                    }
                );
            };
        };
    }
}
