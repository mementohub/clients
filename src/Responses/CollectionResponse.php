<?php

namespace iMemento\Clients\Responses;

use Illuminate\Support\Collection;
use GuzzleHttp\Psr7\Response;

class CollectionResponse extends Collection
{
    use JsonWrapper;

    public function __construct(Response $response)
    {
        $this->boot($response);
        if (property_exists($this->json, 'data')) {
            return parent::__construct($this->json->data);
        }
        return parent::__construct($this->json);
    }
}
