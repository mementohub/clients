<?php

namespace iMemento\Clients\Responses;

use GuzzleHttp\Psr7\Response;

trait JsonWrapper
{
    protected $response;
    protected $json;

    protected function boot(Response $response)
    {
        $this->response = $response;
        $this->json = $this->getJson();
    }

    public function response() : Response
    {
        return $this->response;
    }

    protected function getBody()
    {
        $body = $this->response->getBody()->getContents();
        $this->response->getBody()->rewind();
        return $body;
    }

    protected function getJson()
    {
        $body = $this->getBody();
        $json = json_decode($body);
        if (json_last_error() !== 0) {
            throw new \InvalidArgumentException('JSON decode error: ' . json_last_error_msg() . PHP_EOL . $body);
        }
        return $json;
    }

    public function __get($property)
    {
        if (property_exists($this->json, $property)) {
            return $this->json->$property;
        }
        return null;
    }
}
