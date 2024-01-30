<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\Storage;

use DalPraS\OAuth2\Client\Provider\GotoWebinarResourceOwner;
use League\OAuth2\Client\Token\AccessToken;

interface TokenStorageInterface 
{
    /**
     * key prefix used for storing the information in redis.
     */
    const PREFIX = 'G2W_';

    /**
     * Fetch the access token for a given organizer with. 
     */
    public function fetchToken(string $organizerKey): ?AccessToken;
    
    /**
     * Save the accessToken with the specified id.
     * Set an expiration of $seconds (default 365 days) for the token.
     */
    public function saveToken(AccessToken $accessToken, GotoWebinarResourceOwner $owner, int $seconds = 86400 * 365): self;
}

