<?php

namespace iMemento\Clients;

class Logs extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'service';

    protected $should_queue = false;

    // region CRUD
    public function listLogs(array $query = [])
    {
        return $this->list('logs', $query);
    }

    public function createLog(array $attributes = [])
    {
        return $this->queue()->post('logs', $attributes);
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
