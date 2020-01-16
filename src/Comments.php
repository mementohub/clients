<?php

namespace iMemento\Clients;

class Comments extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'service';

    // region CRUD
    public function listComments(array $query = [])
    {
        return $this->list('comments', $query);
    }

    public function createComment(array $attributes = [])
    {
        return $this->post('comments', $attributes);
    }

    public function showComment(int $id)
    {
        return $this->get("comments/$id");
    }

    public function updateComment(int $id, array $attributes = [])
    {
        return $this->put("comments/$id", $attributes);
    }

    public function destroyComment(int $id)
    {
        return $this->delete("comments/$id");
    }
    // endregion

    // region Operational
    public function approveComment(int $id)
    {
        return $this->put("comments/$id/approve");
    }

    public function getResourceCommentSum(array $query = [])
    {
        return $this->get('comments/sum', $query);
    }
    // endregion
}
