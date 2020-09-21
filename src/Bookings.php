<?php

namespace iMemento\Clients;

class Bookings extends AbstractClient
{
    protected $mode = 'critical';

    protected $authorization = 'none';

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

    public function staticsHotelsLocation(array $query = [])
    {
        return $this->list('statics/hotels-location', $query);
    }

    public function staticsHotel(int $id, array $query = [])
    {
        return $this->get("statics/hotels/$id", $query);
    }

    // reservations
    public function reservation(string $code)
    {
        return $this->get("/reservations/$code");
    }
    
    public function reservationBook(array $arguments)
    {
        return $this->post("/reservations/book", $arguments);
    }

    public function reservationCancel(string $code)
    {
        return $this->delete("/reservations/$code/cancel");
    }
    
    public function reservationNoShow(string $code)
    {
        return $this->delete("/reservations/$code/no-show");
    }

    public function reservationUpdate(string $code, array $arguments = [])
    {
        return $this->put("/reservations/$code/change", $arguments);
    }

}
