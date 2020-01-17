<?php

namespace iMemento\Clients\Middleware;

use GuzzleHttp\Middleware as GuzzleMiddleware;
use Psr\Http\Message\RequestInterface;

class AuthMiddleware
{
    protected $method;
    protected $token;

    public function __construct(string $method = 'none', $token = null)
    {
        $this->method = $method;
        $this->token = $token;
    }

    protected function shouldAuthenticate()
    {
        return ($this->method != 'none');
    }

    protected function token()
    {
        switch ($this->method) {
            case 'token':
                return $this->token;
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
                return $request->withHeader('Authorization', 'Bearer ' . $this->token());
            }
        );
    }
}
