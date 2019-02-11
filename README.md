# Citrix GoToWebinar Provider for OAuth 2.0 Client

This package provides Citrix GoToWebinar OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

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

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getNickname());

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
    $provider = new \DalPraS\OAuth2\Client\Provider\GotoWebinar([
        'clientId'                => 'demoapp',    // The client ID assigned to you by the provider
        'clientSecret'            => 'demopass',   // The client password assigned to you by the provider
        'redirectUri'             => 'http://example.com/your-redirect-url/',
    ]);

    $accessToken = getAccessTokenFromYourDataStore();

    $resWebinar = new \DalPraS\OAuth2\Client\Resources\Webinar($provider, $accessToken);
    try {
        $data = $resWebinar->getWebinars();

        // or
        $data = $resWebinar->getPast(new \DateTime('-1 year'));

        // or
        $data = $resWebinar->getWebinar('webinarKey');

        // or
        $data = $resWebinar->deleteWebinar('webinarKey');

        // or
        $data = $resWebinar->getUpcoming();

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

        return json_encode($data);

    } catch (GotoWebinarProviderException $e) {
        // return a psr7 response message (using for example zend\diactoros)
        return $e->generateHttpResponse(new \Zend\Diactoros\Response());

    } catch (\Exception $e) {
        die($e->getMessage());

    }
```

## Testing

```bash
    ./vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](https://github.com/dalpras/oauth2-gotowebinar/blob/master/LICENSE) for more information.
