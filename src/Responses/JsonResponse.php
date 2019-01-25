<?php

namespace iMemento\Clients\Responses;

use GuzzleHttp\Psr7\Response;

class JsonResponse
{
    use JsonWrapper;

    protected $attributes;

    public function __construct(Response $response)
    {
        $this->boot($response);

        if (property_exists($this->json, 'data')) {
            $this->attributes = $this->json->data;
            return;
        }
        $this->attributes = $this->json;
    }

    public function all()
    {
        return $this->attributes;
    }
}
