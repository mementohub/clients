<?php

namespace iMemento\Clients;

class ExchangeRates extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'service';

    public function getBaseUri()
    {
        return config('imemento-sdk.exchange-rates.base_uri');
    }

    /**
     * Operational endpoints
     */
    public function getLatest()
    {
        return $this->list('latest');
    }


}
