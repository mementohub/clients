<?php

namespace iMemento\Clients;

class Events extends AbstractClient
{
    protected $mode = 'silent';

    protected $authorization = 'service';

    protected $should_queue = true;

    protected $config_name = 'event-bus';

    public function emit(array $event = [])
    {
        $service = $service ?? config('app.name');

        $attributes = array_merge(['service' => $service], $event);
        return $this->post('listen', $attributes);
    }
}
