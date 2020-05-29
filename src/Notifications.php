<?php

namespace iMemento\Clients;

class Notifications extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'none';

    public function email(array $parameters = [])
    {
        return $this->post('email', $parameters);
    }

    public function sms(array $parameters = [])
    {
        return $this->post('sms', $parameters);
    }

    public function slack(array $parameters = [])
    {
        return $this->post('slack', $parameters);
    }
}
