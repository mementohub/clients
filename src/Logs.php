<?php

namespace iMemento\Clients;

class Logs extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'service';

    public function getBaseUri()
    {
        return config('imemento-sdk.logs.base_uri');
    }

    // region CRUD
    public function listLogs(array $query = [])
    {
        return $this->list('logs', $query);
    }

    public function createLog(array $attributes = [])
    {
        return $this->post('logs', $attributes);
    }

    public function showLog(int $id)
    {
        return $this->get("logs/$id");
    }

    public function updateLog(int $id, array $attributes = [])
    {
        return $this->put("logs/$id", $attributes);
    }

    public function destroyLog(int $id)
    {
        return $this->delete("logs/$id");
    }
    // endregion
}
