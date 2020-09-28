<?php

namespace iMemento\Clients\Middleware;

use Psr\Http\Message\RequestInterface;

class AuthMiddleware
{
    protected $nextHandler;
    protected $method;
    protected $token;

    public function __construct(callable $nextHandler, string $method = 'none', $token = null)
    {
        $this->nextHandler = $nextHandler;
        $this->method      = $method;
        $this->token       = $token;
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

    public function __invoke(RequestInterface $request, array $options)
    {
        $fn = $this->nextHandler;

        if (!$this->shouldAuthenticate()) {
            return $fn($request, $options);
        }

        return $fn($request->withHeader('Authorization', 'Bearer ' . $this->token()), $options);
    }
}
