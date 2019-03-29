<?php

namespace iMemento\Clients;

class Events extends AbstractClient
{
    protected $mode = 'silent';

    protected $authorization = 'service';

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
