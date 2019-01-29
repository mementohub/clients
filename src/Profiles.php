<?php

namespace iMemento\Clients;

class Profiles extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'service';

    public function getBaseUri()
    {
        return config('imemento-sdk.profiles.base_uri');
    }

    /**
     * Users CRUD
     */
    public function listUsers(array $query = [])
    {
        return $this->list('users', $query);
    }

    public function createUser(array $attributes = [])
    {
        return $this->post('users', $attributes);
    }

    public function showUser($id)
    {
        return $this->get("users/$id");
    }

    public function updateUser($id, array $attributes = [])
    {
        return $this->put("users/$id", $attributes);
    }

    public function destroyUser($id)
    {
        return $this->delete("users/$id");
    }

    /**
     * Organizations CRUD
     */
    public function listOrganizations(array $query = [])
    {
        return $this->list('organizations', $query);
    }

    public function createOrganization(array $attributes = [])
    {
        return $this->post('organizations', $attributes);
    }

    public function showOrganization($id)
    {
        return $this->get("organizations/$id");
    }

    public function updateOrganization($id, array $attributes = [])
    {
        return $this->put("organizations/$id", $attributes);
    }

    public function destroyOrganization($id)
    {
        return $this->delete("organizations/$id");
    }

    /**
     * Billings CRUD
     */
    public function listBillings(array $query = [])
    {
        return $this->list('billings', $query);
    }

    public function createBilling(array $attributes = [])
    {
        return $this->post('billings', $attributes);
    }

    public function showBilling($id)
    {
        return $this->get("billings/$id");
    }

    public function updateBilling($id, array $attributes = [])
    {
        return $this->put("billings/$id", $attributes);
    }

    public function destroyBilling($id)
    {
        return $this->delete("billings/$id");
    }

    /**
     * Operational endpoints
     */
    public function getUserProfile()
    {
        return $this->asUser()->get("users/me");
    }

    public function getUserOrganizationsByToken($token)
    {
        return $this->withToken($token)->list("users/authentication");
    }

    public function getUserBilling($user_id)
    {
        return $this->get("users/$user_id/billing");
    }

    public function getOrganizationBilling($org_id)
    {
        return $this->get("organizations/$org_id/billing");
    }
}
