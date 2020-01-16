<?php

namespace iMemento\Clients;

class Places extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'none';

    // region CRUD
    public function listPlaces(array $query = [])
    {
        return $this->list('places', $query);
    }

    public function createPlace(array $attributes = [])
    {
        return $this->post('places', $attributes);
    }

    public function showPlace(int $id)
    {
        return $this->get("places/$id");
    }

    public function updatePlace(int $id, array $attributes = [])
    {
        return $this->put("places/$id", $attributes);
    }

    public function destroyPlace(int $id)
    {
        return $this->delete("places/$id");
    }
    // endregion

    // region Operational
    public function search(array $query = [])
    {
        return $this->list('search', $query);
    }

    public function place(string $id, array $with = [])
    {
        return $this->get("place/$id", $with);
    }
    // endregion
}
