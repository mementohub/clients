# iMemento Clients

[![Build Status](https://travis-ci.org/mementohub/clients.svg?branch=master)](https://travis-ci.org/mementohub/clients)
[![Docs Status](https://readthedocs.org/projects/imemento-clients/badge/?version=latest)](https://imemento-clients.readthedocs.io)
[![Latest Stable Version](https://poser.pugx.org/imemento/clients/v/stable)](https://packagist.org/packages/imemento/clients)
[![License](https://poser.pugx.org/imemento/clients/license)](https://packagist.org/packages/imemento/clients)
[![Total Downloads](https://poser.pugx.org/imemento/clients/downloads)](https://packagist.org/packages/imemento/clients)

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
