<?php

namespace iMemento\Clients\Tests\Unit;

use iMemento\Clients\AbstractClient;

class ClientStub extends AbstractClient
{
    protected $mode = 'critical';

    public function getBaseUri()
    {
        return 'stubbed';
    }

    public function getConfig()
    {
        return $this->config();
    }

    public function getMode()
    {
        return $this->mode();
    }

    public function preferredSilentCall()
    {
        $this->preferredSilent();
        return $this;
    }

    public function silentCall()
    {
        $this->silent();
        return $this;
    }

    public function call()
    {

    }
}
