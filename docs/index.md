# iMemento Clients

Client library classes for iMemento Services

## Installation

```bash
composer require imemento/clients
```

## Simple usage

As a Laravel Facade using [Real-Time Facades](https://laravel.com/docs/5.7/facades#real-time-facades)

```php
use Facades\iMemento\Clients\Profiles;

//...

public function index()
{
    $user = Profiles::showUser(1234);
}
```
