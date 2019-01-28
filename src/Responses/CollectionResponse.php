<?php

namespace iMemento\Clients\Responses;

use Illuminate\Support\Collection;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class CollectionResponse extends Collection implements ResponseInterface
{
    use JsonResponseWrapper, CollectionInterceptor;

    public function __construct($response)
    {
        $this->boot($response);
        if (property_exists($this->json, 'data')) {
            return parent::__construct($this->json->data);
        }
        return parent::__construct($this->json);
    }
}
