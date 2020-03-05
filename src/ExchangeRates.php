<?php

namespace iMemento\Clients;

class ExchangeRates extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'none';

    protected $config_name = 'exchange-rates';

    /**
     * Operational endpoints
     */
    public function getLatest()
    {
        return $this->list('latest');
    }


}
