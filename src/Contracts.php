<?php

namespace iMemento\Clients;

class Contracts extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'service';

    public function listContracts(array $query = [])
    {
        return $this->list('contracts', $query);
    }

    public function createContract(array $attributes = [])
    {
        return $this->post('contracts', $attributes);
    }

    public function showContract(int $id)
    {
        return $this->get("contracts/$id");
    }

    public function updateContract(int $id, array $attributes = [])
    {
        return $this->put("contracts/$id", $attributes);
    }

    public function destroyContract(int $id)
    {
        return $this->delete("contracts/$id");
    }
}
