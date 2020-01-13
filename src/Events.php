<?php

namespace iMemento\Clients;

class Events extends AbstractClient
{
    protected $mode = 'silent';

    protected $authorization = 'service';

    protected $should_queue = true;

    protected $config_name = 'event-bus';

    public function emit($event, array $payload = null, string $service = null, string $token = null, int $delay = null)
    {
        $service = $service ?? config('app.name');

        // allow passing either a keyed array with all the variables or the variables as method params
        $attributes = is_array($event) ?
            array_merge(['service' => $service], $event) :
            compact('event', 'payload', 'service', 'token', 'delay');

        return $this->post('listen', $attributes);
    }
}
