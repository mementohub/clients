<?php

namespace iMemento\Clients\Middleware;

use GuzzleHttp\Middleware as GuzzleMiddleware;
use Psr\Http\Message\RequestInterface;

class AuthMiddleware
{
    protected $config = [];
    protected $default = '';

    public function __construct($config, $default)
    {
        $this->config = $config;
        $this->default = $default;
    }

    protected function shouldAuthenticate()
    {
        return ($this->method() !== 'none');
    }

    protected function method()
    {
        return $this->config['authorization']['requested'] ?? $this->default;
    }

    protected function token()
    {
        switch ($this->method()) {
            case 'token':
                return $this->config['token'];
                break;
            case 'user':
                return auth()->user()->token;
                break;
            case 'service':
                return \iMemento\SDK\Auth\Helper::authenticate();
                break;
            default:
                return '';
                break;
        }
        return '';
    }

    public function handler()
    {
        return GuzzleMiddleware::mapRequest(
            function (RequestInterface $request) {
                if (!$this->shouldAuthenticate()) {
                    return $request;
                }
                return $request->withHeader('Authentication', 'Bearer ' . $this->token());
            }
        );
    }
}
