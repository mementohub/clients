<?php

namespace iMemento\Clients;

class Places extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'service';

    public function getBaseUri()
    {
        return config('imemento-sdk.places.base_uri');
    }

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
    public function search(string $query, $locale = 'en', $size = 5, $country = null, $type = null)
    {
        $query = compact('query', 'locale', 'size', 'country', 'type');
        return $this->get('search', $query);
    }

    public function place(string $id, array $with = [])
    {
        $query = compact($with);
        return $this->get("place/$id", $query);
    }
    // endregion
}
