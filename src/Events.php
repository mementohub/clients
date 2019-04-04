<?php

namespace iMemento\Clients;

class Events extends AbstractClient
{
    protected $mode = 'silent';

    protected $authorization = 'service';

    protected $should_queue = true;

    public function getBaseUri()
    {
        return config('imemento-sdk.eventbus.base_uri');
    }

    public function emit(array $attributes = [])
    {
        $attributes = array_merge(['service' => config('app.name')], $attributes);

        return $this->post('listen', $attributes);
    }
}
