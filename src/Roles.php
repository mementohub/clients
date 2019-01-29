<?php

namespace iMemento\Clients;

class Roles extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'service';

    public function getBaseUri()
    {
        return config('imemento-sdk.roles.base_uri');
    }

    /**
     * Services CRUD
     */
    public function listServices(array $query = [])
    {
        return $this->list('services', $query);
    }

    public function createService(array $attributes = [])
    {
        return $this->post('services', $attributes);
    }

    public function showService($id)
    {
        return $this->get("services/$id");
    }

    public function updateService($id, array $attributes = [])
    {
        return $this->put("services/$id", $attributes);
    }

    public function destroyService($id)
    {
        return $this->delete("services/$id");
    }

    /**
     * Roles CRUD
     */
    public function listRoles(array $query = [])
    {
        return $this->list('roles', $query);
    }

    public function createRole(array $attributes = [])
    {
        return $this->post('roles', $attributes);
    }

    public function showRole($id)
    {
        return $this->get("roles/$id");
    }

    public function updateRole($id, array $attributes = [])
    {
        return $this->put("roles/$id", $attributes);
    }

    public function destroyRole($id)
    {
        return $this->delete("roles/$id");
    }

    /**
     * User Roles
     */
    public function showUserRoles($id, array $query = [])
    {
        return $this->list("users/$id/roles", $query);
    }

    public function attachUserRole($user_id, $role_id)
    {
        return $this->post("users/$user_id/roles/$role_id");
    }

    public function detachUserRole($user_id, $role_id)
    {
        return $this->delete("users/$user_id/roles/$role_id");
    }

    /**
     * Roles for the user's token
     */
    public function getUserRolesByToken($token)
    {
        return $this->withToken($token)->list("users/authentication");
    }

}
