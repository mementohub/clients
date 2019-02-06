# Usage

### Dependency Injection

```php
use iMemento\Clients\Profiles;

//...

public function index(Profiles $profiles)
{
    $user = $profiles->showUser(1234);
}
```

### Instantiation

```php
use iMemento\Clients\Profiles;

//...

public function index()
{
    $profiles = new Profiles();
    $user = $profiles->showUser(1234);
}
```

### Facade

```php
use Facades\iMemento\Clients\Profiles;

//...

public function index()
{
    $user = Profiles::showUser(1234);
}
```


## Modifier Methods

In the following examples the Facade method will be used for simplicity.

### Silent failures

In case of a bad response it will log the error and return an empty object so that you can continue with your code.
This method may be used for non critical calls

```php
$user = Profiles::silent()->showUser(1234);
```

### Critical failures

This approach will always throw an exception and halt script execution. Of course you can use a `try { } catch ()` block.

```php
$user = Profiles::critical()->showUser(1234);
```

### Attempts

The below call will fail only after trying 3 times. Use this in conjuction with `silent()` if you want to continue the
script without failure in case none of the attempts are successfull.

```php
$user = Profiles::retries(3)->showUser(1234);
```

### Async

When using multiple services at the same time, calling the servers async may help with overall execution time.
You can refer to the [Guzzle official docs](http://docs.guzzlephp.org/en/stable/quickstart.html#async-requests) for more information on placing asynchronous calls.

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

The authentication is handled under the hood provided the proper environment variables are
defined, as expected by the `imemento/sdk-auth` package.

Most clients use the underlying consumer service identity to place the calls to the APIs. Check the particular
client to see the expected authentication method.

You can specify alternative authentication methods at rutime:

### As a service

The proper environment variables need to be set.

```php
$org = Profiles::asService()->showOrganization(1234);
```

### As the logged in user

```php
$org = Profiles::asUser()->showOrganization(1234);
```

### As a particular user

```php
$org = Profiles::as($user)->showOrganization(1234);
```

### With a particular token

```php
$org = Profiles::withToken($token)->showOrganization(1234);
```

### Anonymously

```php
$org = Profiles::anonymously()->showOrganization(1234);
```

## Responses

Most client libraries will automatically wrap the response and provide a JSON representation of it. Usually, the `list` methods
will wrap the response in a Collection, so that you can start manipulating the data out of the box.

On the other hand, if you need to have access to the original response (body, status code, etc.) you can retrieve it with the
`response()` method. All general response methods are still available (`getBody`, `getStatusCode`, `getHeaders`, etc.)

```php
$org = Profiles::showOrganization(1234);

// the original response
$response = $org->response();

// the original headers
$headers = $org->getHeaders();
```
