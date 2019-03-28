<?php

namespace iMemento\Clients;

class Events extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'service';

    public function getBaseUri()
    {
        return config('imemento-sdk.eventbus.base_uri');
    }

    public function emitEvent(array $attributes = [])
    {
        return $this->post('listen', $attributes);
    }
}
