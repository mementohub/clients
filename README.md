# iMemento Clients

[![Build Status](https://github.com/mementohub/clients/workflows/Testing/badge.svg)](https://github.com/mementohub/clients/actions)
[![Docs Status](https://img.shields.io/readthedocs/imemento-clients)](https://imemento-clients.readthedocs.io)
[![Latest Stable Version](https://img.shields.io/packagist/v/imemento/clients)](https://packagist.org/packages/imemento/clients)
[![License](https://img.shields.io/packagist/l/imemento/clients)](https://packagist.org/packages/imemento/clients)
[![Total Downloads](https://img.shields.io/packagist/dt/imemento/clients)](https://packagist.org/packages/imemento/clients)

Client library classes for iMemento Services

Check the full docs in here [https://imemento-clients.readthedocs.io](https://imemento-clients.readthedocs.io)

## Installation

```bash
composer require imemento/clients
```

## Quick Usage

```php
use Facades\iMemento\Clients\Profiles;

//...

public function index()
{
    $user = Profiles::showUser(1234);
}
```


## Modifier Methods

```php
// it returns an empty response on failure
$user = Profiles::silent()->showUser(1234);
// it throws an exception on failure
$user = Profiles::critical()->showUser(1234);
// it retries the request 3 times if it fails
$user = Profiles::retries(3)->showUser(1234);
```

### Async

```php
use GuzzleHttp\Promise;

// ...

$promises = [
    'profiles'      => Profiles::async()->showUser(1234),
    'roles'         => Roles::async()->listRoles(),
];

$results = Promise\settle($promises)->wait();
```

## Authentication

```php
// as the current running service (the env variables need to be set)
$org = Profiles::asService()->showOrganization(1234);
// as the currently logged in user
$org = Profiles::asUser()->showOrganization(1234);
// as the given user
$org = Profiles::as($user)->showOrganization(1234);
// with the provided token
$org = Profiles::withToken($token)->showOrganization(1234);
// anonymous calls
$org = Profiles::anonymously()->showOrganization(1234);
```
