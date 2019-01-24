<?php

namespace IMemento\SDK;

class Roles extends AbstractClient
{
    public function getBaseUri()
    {
        return config('imemento-sdk.roles.base_uri');
    }

    public function listServices(array $query = [])
    {
        return $this->list('services', $query);
    }

    public function createService(array $attributes = [])
    {
        return $this->create('services', $attributes);
    }

    public function showService($id)
    {
        return $this->show("services/$id");
    }

    public function updateService($id, array $attributes = [])
    {
        return $this->update("services/$id", $attributes);
    }

    public function destroyService($id)
    {
        return $this->destroy("services/$id");
    }

}
