<?php
namespace DalPraS\OAuth2\Client\Storage;

interface TokenStorageInterface {
    
    /**
     * The Domain used for storing the information in redis.
     *
     * @var string
     */
    const STORAGE_DOMAIN = 'G2W_TOKEN_%s';
    
    /**
     * Fetch the access token for a given organizer with. 
     * 
     * @param string $organizerKey
     */
    public function fetchToken(string $organizerKey);
    
    /**
     * Store a token for the current organizer.
     * 
     * @param \League\OAuth2\Client\Token\AccessToken $accessToken
     */
    public function saveToken(\League\OAuth2\Client\Token\AccessToken $accessToken);
    
}

