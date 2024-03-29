# LogMeIn GoToWebinar Provider for OAuth 2.0 Client

This package provides LogMeIn GoToWebinar OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```bash
composer require dalpras/oauth2-gotowebinar
```

## Usage

Usage is the same as The League's OAuth client, using `\DalPraS\OAuth2\Client\Provider\GotoWebinar` as the provider.

### Authorization Code Flow

```php
$development = true;
$provider = new \DalPraS\OAuth2\Client\Provider\GotoWebinar([
    // The client ID assigned to you by the provider
    'clientId'     => 'your gotowebinar client id',
    // The client ID assigned to you by the provider
    'clientSecret' => 'your gotowebinar client password',
    'redirectUri'  => 'your redirect uri after authorization'
], [
    // optional
    'httpClient' => new \GuzzleHttp\Client([
        // setup some options for using with localhost
        'verify'  => $development ? false : true,
        // timeout connection
        'timeout' => 60
    ])
]);

$redis = new \Redis();
$redis->connect($host, $port);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);

    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    try {
        // We got an access token, let's now get the user's details
        $owner = $provider->getResourceOwner($token);

        // Saved token in redis for future usage.
        $g2wstorage = new \DalPraS\OAuth2\Client\Storage\RedisTokenStorage($redis);
        $g2wstorage->saveToken($accessToken, $owner);

        // Optional: Now you have a token you can look up a users profile data

        // Use these details to create a new profile
        printf('Hello %s!', $owner->getNickname());

    } catch (GotoWebinarProviderException $e) {
        return $e->generateHttpResponse(new \Zend\Diactoros\Response());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

### Refreshing a token

```php
    $provider = new \DalPraS\OAuth2\Client\Provider\GotoWebinar([
        'clientId'                => 'demoapp',    // The client ID assigned to you by the provider
        'clientSecret'            => 'demopass',   // The client password assigned to you by the provider
        'redirectUri'             => 'http://example.com/your-redirect-url/',
    ]);

    $existingAccessToken = getAccessTokenFromYourDataStore();

    if ($existingAccessToken->hasExpired()) {
        $newAccessToken = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $existingAccessToken->getRefreshToken()
        ]);
        // Purge old access token and store new access token to your data store.
    }
```

### Using GotoWebinar WEB API

Interaction with the GoToWebinar API is very easy.

```php

    ...

    $provider = new \DalPraS\OAuth2\Client\Provider\GotoWebinar([
        'clientId'                => 'demoapp',    // The client ID assigned to you by the provider
        'clientSecret'            => 'demopass',   // The client password assigned to you by the provider
        'redirectUri'             => 'http://example.com/your-redirect-url/',
    ]);

    // You can get the Resources using a previously stored (in redis) AccessToken
    // The ResourceLoader handle the AccessToken loading for you 
    $g2wstorage = new \DalPraS\OAuth2\Client\Storage\RedisTokenStorage($redis);
    $loader = new \DalPraS\OAuth2\Client\Loader\ResourceLoader($g2wstorage, $provider);

    /* @var $resWebinar \DalPraS\OAuth2\Client\Resources\Webinar */
    $resWebinar = $loader->getWebinarResource($organizerKey);

    /* @var $resWebinar \DalPraS\OAuth2\Client\Resources\Webinar */
    $resRegistrant = $loader->getRegistrantResource($organizerKey);

    // ... or if you just had the AccessToken in your hands, instance the Resources needed: 
    // $resWebinar = new \DalPraS\OAuth2\Client\Resources\Webinar($provider, $accessToken);
    // $resRegistrant = new \DalPraS\OAuth2\Client\Resources\Registrant($provider, $accessToken);

    try {
        $data = $resWebinar->getWebinarsByOrganizer(new \DateTime('-1 year'), new \DateTime('+1 year'), 0, 10);

        // or 
        $data = $resWebinar->getWebinarsByAccount(new \DateTime('-1 year'), new \DateTime('+1 year'), 0, 10);

        // or 
        $data = $resWebinar->getWebinar('webinarKey');

        // or
        $data = $resWebinar->deleteWebinar('webinarKey');

        // or
        // helper for changing timezone to utc
        $dateUtcHelper = new \DalPraS\OAuth2\Client\Helper\DateUtcHelper();
        $data =  $resWebinar->createWebinar([
                "subject" => "My first webinar",
                "times" => [[
                    "startTime" => $dateUtcHelper->date2utc(new \DateTime('+1 day')),
                    "endTime" => $dateUtcHelper->date2utc(new \DateTime('+2 days')),
                ]],
            ]);

        // or
        $data = $resRegistrant->getRegistrants('webinarKey');

        // or
        $data = $resRegistrant->getRegistrant('webinarKey', 'registrantKey');

        // or
        $data = $resRegistrant->getRegistrantByEmail('webinarKey', 'myemail@gmail.com');

        // or
        $data = $resRegistrant->createRegistrant('webinarKey', [
                'firstName' => 'Ronnie',
                'lastName' => 'Test',
                'email' => 'test@gmail.com'
            ]);

        // or
        $data = $resRegistrant->deleteRegistrant('webinarKey', 'registrantKey');

        // data is ResultSetInterface = ArrayObject
        return json_encode($data->getArrayCopy());

    } catch (GotoWebinarProviderException $e) {
        // return a psr7 response message (using for example zend\diactoros)
        return $e->generateHttpResponse(new \Zend\Diactoros\Response());

    } catch (\Exception $e) {
        die($e->getMessage());

    }
```

### Using Storage

Storage has been introduced for storing Organizer's accessTokens in a repository (only \Redis has been implemented).  
ResourceLoader use this repository to perform GotoWebinar information access in an easy way.
With this feature registrants can subscribe to a course using the accessToken stored by Organizers in the repository.  
ResourceLoader manage the data in a centralized way.

```php
    $provider = new \DalPraS\OAuth2\Client\Provider\GotoWebinar([
        'clientId'                => 'demoapp',
        'clientSecret'            => 'demopass',
        'redirectUri'             => 'http://example.com/your-redirect-url/'
    ]);

    // Organizers stored their AccessTokens in storage
    $storage = new \DalPraS\OAuth2\Client\Storage\RedisTokenStorage($redisInstance);

    // Connecting ResourceLoader to Storage is possible to retrieve information from multiple organizers
    $resourceLoader = new \DalPraS\OAuth2\Client\Loader\ResourceLoader($storage, $provider);

    // webinars from Organizer 1
    $webinars = $resourceLoader->getWebinarResource($organizerKey1)->getWebinars();

    // registrants from Organizer 2
    $registrants = $resourceLoader->getRegistrantResource($organizerKey2)->getRegistrants($webinarKey);

```


## Testing

```bash
    ./vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](https://github.com/dalpras/oauth2-gotowebinar/blob/master/LICENSE) for more information.
