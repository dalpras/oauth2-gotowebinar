<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\Storage;

use DalPraS\OAuth2\Client\Provider\GotoWebinarResourceOwner;
use League\OAuth2\Client\Token\AccessToken;
use Predis\Client as PredisClient;

class PredisTokenStorage implements TokenStorageInterface 
{
    public function __construct(
        private PredisClient $redis
    ) {}
 
    /**
     * Retrieves an accessToken from redis with the specified id.
     */
    public function fetchToken(string $organizerKey): ?AccessToken
    {
        // Check that the token has been saved in Redis
        $redisKey = self::PREFIX . $organizerKey;
        if ($this->redis->exists($redisKey)) {
            $data = json_decode($this->redis->get($redisKey), true);
            if ( !empty($data) ) {
                return new AccessToken($data);
            }
        }
        return null;
    }
    
    /**
     * Save the accessToken with the specified id.
     * Set an expiration of $seconds (default 365 days) for the token.
     */
    public function saveToken(AccessToken $accessToken, GotoWebinarResourceOwner $owner, int $seconds = 86400 * 365): self
    {
        // Store token for future usage
        $redisKey = self::PREFIX . $owner->getKey();
        $this->redis->set($redisKey, json_encode($accessToken->jsonSerialize()));
        $this->redis->expireAt($redisKey, time() + $seconds);
        return $this;
    }
}

