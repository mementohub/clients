<?php

namespace iMemento\Clients\Responses;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\StreamInterface;

trait JsonResponseWrapper
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

    protected function retrieveBody()
    {
        $body = $this->response->getBody()->getContents();
        $this->response->getBody()->rewind();
        return $body;
    }

    protected function getJson()
    {
        $body = $this->retrieveBody();
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


    /**
     * Response Interface
     */

     public function getStatusCode()
     {
         return $this->response()->getStatusCode();
     }

     public function withStatus($code, $reasonPhrase = '')
     {
         return $this->response()->withStatus($code, $reasonPhrase = '');
     }

     public function getReasonPhrase()
     {
         return $this->response()->getReasonPhrase();
     }

     /**
      * Message Interface
      */
    public function getProtocolVersion()
    {
        return $this->response()->getProtocolVersion();
    }
    
    public function withProtocolVersion($version)
    {
        return $this->response()->withProtocolVersion($version);
    }

    public function getHeaders()
    {
        return $this->response()->getHeaders();
    }

    public function hasHeader($name)
    {
        return $this->response()->hasHeader($name);
    }

    public function getHeader($name)
    {
        return $this->response()->getHeader($name);
    }

    public function getHeaderLine($name)
    {
        return $this->response()->getHeaderLine($name);
    }

    public function withHeader($name, $value)
    {
        return $this->response()->withHeader($name, $value);
    }

    public function withAddedHeader($name, $value)
    {
        return $this->response()->withAddedHeader($name, $value);
    }

    public function withoutHeader($name)
    {
        return $this->response()->withoutHeader($name);
    }

    public function getBody()
    {
        return $this->response()->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        return $this->response()->withBody($body);
    }
}
