<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\Loader;

use DalPraS\OAuth2\Client\Provider\GotoWebinar;
use DalPraS\OAuth2\Client\Provider\GotoWebinarResourceOwner;
use DalPraS\OAuth2\Client\Resources\Attendee;
use DalPraS\OAuth2\Client\Resources\CoOrganizer;
use DalPraS\OAuth2\Client\Resources\Registrant;
use DalPraS\OAuth2\Client\Resources\Session;
use DalPraS\OAuth2\Client\Resources\Webinar;
use DalPraS\OAuth2\Client\Storage\TokenStorageInterface;
use League\OAuth2\Client\Token\AccessToken;

/**
 * Store Organizer's accessTokens in a repository.
 */
class ResourceLoader 
{
    public function __construct(
        private TokenStorageInterface $storage, 
        private GotoWebinar $provider
    ) {}

    /**
     * Check if the token is valid and in case refreshes the token and
     * save it in your current storage.
     */
    protected function refreshToken(?AccessToken $accessToken): ?AccessToken 
    {
        switch (true) {
            case $accessToken === null:
                break;

            case $accessToken->hasExpired():
                $accessToken = $this->provider->getAccessToken('refresh_token', [
                    'refresh_token' => $accessToken->getRefreshToken()
                ]);
                $owner = $this->provider->getResourceOwner($accessToken);
                // Purge old access token and store new access token to your data store.
                $this->storage->saveToken($accessToken, $owner);
                break;
        }
        return $accessToken;
    }

    /**
     * Get the Webinar resource
     */
    public function getWebinarResource(string $organizerKey): ?Webinar
    {
        $accessToken = $this->refreshToken($this->storage->fetchToken($organizerKey));
        return $accessToken ? (new Webinar($this->provider, $accessToken)) : null;
    }

    /**
     * Get the Registrant resource
     */
    public function getRegistrantResource(string $organizerKey): ?Registrant
    {
        $accessToken = $this->refreshToken($this->storage->fetchToken($organizerKey));
        return $accessToken ? (new Registrant($this->provider, $accessToken)) : null;
    }

    /**
     * Get the ResourceOwner using the storage with the OrganizerKey param.
     */
    public function getResourceOwner(string $organizerKey): ?GotoWebinarResourceOwner
    {
        $accessToken = $this->refreshToken($this->storage->fetchToken($organizerKey));
        return $accessToken ? $this->provider->getResourceOwner($accessToken) : null;
    }

    /**
     * Get the Attenee resource
     */
    public function getAttendeesResource(string $organizerKey): ?Attendee
    {
        $accessToken = $this->refreshToken($this->storage->fetchToken($organizerKey));
        return $accessToken ? (new Attendee($this->provider, $accessToken)) : null;
    }

    /**
     * Get the Attenee resource
     */
    public function getSessionResource(string $organizerKey): ?Session
    {
        $accessToken = $this->refreshToken($this->storage->fetchToken($organizerKey));
        return $accessToken ? (new Session($this->provider, $accessToken)) : null;
    }

    /**
     * Get the CoOrganizer resource
     */
    public function getOrganizerResource(string $organizerKey): ?CoOrganizer
    {
        $accessToken = $this->refreshToken($this->storage->fetchToken($organizerKey));
        return $accessToken ? (new CoOrganizer($this->provider, $accessToken)) : null;
    }
}