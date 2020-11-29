<?php
namespace DalPraS\OAuth2\Client\Loader;

use DalPraS\OAuth2\Client\Storage\TokenStorageInterface;
use DalPraS\OAuth2\Client\Provider\GotoWebinar;

class ResourceLoader {

    /**
     * Token storage.
     *
     * @var \DalPraS\OAuth2\Client\Storage\TokenStorageInterface
     */
    private $storage;

    /**
     * @var \DalPraS\OAuth2\Client\Provider\GotoWebinar
     */
    private $provider;

    public function __construct(TokenStorageInterface $storage, GotoWebinar $provider) {
        $this->storage  = $storage;
        $this->provider = $provider;
    }

    /**
     * Check if the token is valid and in case refreshes the token and
     * save it in your current storage.
     *
     * @param \League\OAuth2\Client\Token\AccessToken|null $accessToken
     * @return \League\OAuth2\Client\Token\AccessTokenInterface
     */
    protected function refreshToken(?\League\OAuth2\Client\Token\AccessToken $accessToken) {
        switch (true) {
            case $accessToken === null:
                break;

            case $accessToken->hasExpired():
                $accessToken = $this->provider->getAccessToken('refresh_token', [
                    'refresh_token' => $accessToken->getRefreshToken()
                ]);
                // Purge old access token and store new access token to your data store.
                $this->storage->saveToken($accessToken);
                break;
        }
        return $accessToken;
    }

    /**
     * Get the Webinar resource
     *
     * @param string $organizerKey
     * @return \DalPraS\OAuth2\Client\Resources\Webinar|NULL
     */
    public function getWebinarResource(string $organizerKey) {
        $accessToken = $this->refreshToken($this->storage->fetchToken($organizerKey));
        return $accessToken ? (new \DalPraS\OAuth2\Client\Resources\Webinar($this->provider, $accessToken)) : null;
    }

    /**
     * Get the Registrant resource
     *
     * @param string $organizerKey
     * @return \DalPraS\OAuth2\Client\Resources\Registrant|NULL
     */
    public function getRegistrantResource(string $organizerKey) {
        $accessToken = $this->refreshToken($this->storage->fetchToken($organizerKey));
        return $accessToken ? (new \DalPraS\OAuth2\Client\Resources\Registrant($this->provider, $accessToken)) : null;
    }

    /**
     * Get the ResourceOwner using the storage with the OrganizerKey param.
     *
     * @param string $organizerKey
     * @return \DalPraS\OAuth2\Client\Resources\Registrant|NULL
     */
    public function getResourceOwner(string $organizerKey) {
        $accessToken = $this->refreshToken($this->storage->fetchToken($organizerKey));
        return $accessToken ? $this->provider->getResourceOwner($accessToken) : null;
    }

    /**
     * Get the Attenee resource
     *
     * @param string $organizerKey
     * @return \DalPraS\OAuth2\Client\Resources\Attendee|NULL
     */
    public function getAttendeesResource(string $organizerKey)
    {
        $accessToken = $this->refreshToken($this->storage->fetchToken($organizerKey));
        return $accessToken ? (new \DalPraS\OAuth2\Client\Resources\Attendee($this->provider, $accessToken)) : null;
    }

    /**
     * Get the Attenee resource
     *
     * @param string $organizerKey
     * @return \DalPraS\OAuth2\Client\Resources\Session|NULL
     */
    public function getSessionResource(string $organizerKey)
    {
        $accessToken = $this->refreshToken($this->storage->fetchToken($organizerKey));
        return $accessToken ? (new \DalPraS\OAuth2\Client\Resources\Session($this->provider, $accessToken)) : null;
    }

    /**
     * @param string $organizerKey
     * @return \DalPraS\OAuth2\Client\Resources\CoOrganizer|null
     */
    public function getOrganizerResource(string $organizerKey)
    {
        $accessToken = $this->refreshToken($this->storage->fetchToken($organizerKey));
        return $accessToken ? (new \DalPraS\OAuth2\Client\Resources\CoOrganizer($this->provider, $accessToken)) : null;
    }
}

