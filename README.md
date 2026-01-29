API Client
===
<b>This library makes an abstraction layer for REST APIs.<br>
It allows mapping API resources to your application models.</b>

Note
---
<b><u>This library is under development! <br> 
Many things can be changed, and many new features can be added.</u></b>

Symfony Note
---
This library is not tied to a Symfony framework, but there is a Symfony bundle available: [KrzysztofMoskalik/api-client-bundle](https://github.com/KrzysztofMoskalik/api-client-bundle).

Features:
---
- [x] Easy to use
- [x] Flexible
- [x] Easy to extend
- [x] Works the best with standard JSON REST APIs 
- [x] Supports multiple APIs
- [x] Supports multiple authentication methods
- [x] Supports multiple endpoints per same resource (with the same HTTP method)

Simple configuration
---

Api Client does not come with a builtin serializer, so first you need to define your own one.<br>
For example:

```PHP
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

$serializer = new Serializer(
    [
        new ObjectNormalizer(),
        new ArrayDenormalizer(),
    ],
    [
        new JsonEncoder(),
    ]
);
```

Same thing with HTTP Client:

```PHP
$httpClient = new GuzzleHttp\Client();
```

Finally, you can create an Api Client instance with a simple configuration:

```PHP
use App\Model\User;
use KrzysztofMazur\Api\Api;
use KrzysztofMazur\Api\Client;

$client = new Client();

$client->globals
    ->setSerializer($serializer)
    ->setHttpClient($httpClient)

$client->addApi(
    new Api('my_api_name')
        ->setBaseUrl('https://my-api.com/api/')
        ->generic(User::class)
);
```

This will create repository for all CRUD operations on User resource that will be represented by `App\Model\User` class.

Usage
---
With this simple configuration you can now use Api Client to interact with your API:

```PHP
$repository = $client->getRepository(User::class);

$user = $repository->get(2340); // fetch user with id 2340

$users = $repository->list(); // fetch all users

$repository->delete(3671); // delete user with id 3671

$user = new User(
    'John Doe',
    'john.doe@example.com'
);
$repository->create($user); // create new user

$user->setEmail('john.doe@lorem.com');
$repository->update($user); // update user

$user->setEmail('john.doe@ipsum.com');
$repository->patch($user); // partial update user
```

Full config reference
---
Work in progress...

Todos
---
- [ ] handling filtering and sorting on list operations
- [ ] handling nesting data paths
- [ ] implement bearer auth
