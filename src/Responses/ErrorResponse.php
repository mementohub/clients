<?php

namespace iMemento\Clients\Responses;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\GuzzleException;

class ErrorResponse extends Response
{
    protected $exception;

    public function __construct(GuzzleException $e)
    {
        $this->exception = $e;
        return parent::__construct(
            $e->getCode(), 
            $e->getResponse()->getHeaders(), 
            "{}"
        );
    }

    public function exception()
    {
        return $this->exception;
    }
}
