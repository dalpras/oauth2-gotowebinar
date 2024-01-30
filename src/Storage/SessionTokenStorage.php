<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\Storage;

use DalPraS\OAuth2\Client\Provider\GotoWebinarResourceOwner;
use League\OAuth2\Client\Token\AccessToken;

class SessionTokenStorage implements TokenStorageInterface 
{
    /**
     * Retrieves an accessToken from redis with the specified id.
     * 
     * @todo not working
     */
    public function fetchToken(string $organizerKey): ?AccessToken
    {
        return new AccessToken();
    }

    /**
     * Save the accessToken with the specified id.
     * Set an expiration of $seconds (default 365 days) for the token.
     * 
     * @todo not working
     */
    public function saveToken(AccessToken $accessToken, GotoWebinarResourceOwner $owner, int $seconds = 86400 * 365): self
    {
        return $this;
    }
}

