<?php

namespace iMemento\Clients;

class Bookings extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'none';

    public function getBaseUri()
    {
        return config('imemento-sdk.bookings.base_uri');
    }

    // search
    public function searchHotels(array $query = [])
    {
        return $this->list('search/hotels', $query);
    }

    public function searchHotel(int $id, array $query = [])
    {
        return $this->get("search/hotels/$id", $query);
    }

    // static
    public function staticsHotels(array $query = [])
    {
        return $this->list('statics/hotels', $query);
    }

    public function staticsHotel(int $id, array $query = [])
    {
        return $this->get("statics/hotels/$id", $query);
    }
}
