<?php

namespace iMemento\Clients\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use function Opis\Closure\unserialize;

class QueueRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $config;
    protected $request;
    protected $method;
    protected $args;

    public function __construct($config, $request, $method, ...$args)
    {
        $this->config = $config;
        $this->request = $request;
        $this->method = $method;
        $this->args = $args;
    }

    public function handle()
    {
        $config = unserialize($this->config);

        $client = new Client($config);

        return $client->{$this->request}($this->method, ...$this->args);
    }
}
