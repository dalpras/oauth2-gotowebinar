<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\Provider\GotoWebinar;
use League\OAuth2\Client\Token\AccessToken;
use DalPraS\OAuth2\Client\Decorators\AccessTokenDecorator;

abstract class AuthenticatedResourceAbstract {

    /**
     * @var \DalPraS\OAuth2\Client\Provider\GotoWebinar
     */
    protected $provider;

    /**
     * Original League AccessToken
     *
     * @var \League\OAuth2\Client\Token\AccessToken
     */
    protected $accessToken;

    /**
     * @param \DalPraS\OAuth2\Client\Provider\GotoWebinar $provider
     * @param \League\OAuth2\Client\Token\AccessToken $accessToken
     */
    public function __construct(GotoWebinar $provider, AccessToken $accessToken) {
        $this->provider    = $provider;
        $this->accessToken = $accessToken;
    }
    
    /**
     * Replace all the named keys {name} in $text with the respective value from $attribs.
     * If $queryParams are present, they will be added to the url as a query.
     * 
     * @param string $text
     * @param array $attribs
     * @param array $query
     * @return string
     */
    protected function getRequestUrl(string $text, array $attribs = [], array $queryParams = []) {
        $accessToken = new AccessTokenDecorator($this->accessToken);
        
        $attribs = array_replace([
            'organizerKey' => $accessToken->getOrganizerKey(),
            'accountKey'   => $accessToken->getAccountKey(),
            'domain'       => $this->provider->domain
        ], $attribs);
        
        $url = $this->provider->domain . '/G2W/rest/v2' . array_reduce(array_keys($attribs), function ($carry, $key) use ($attribs) {
            return str_replace("{{$key}}", $attribs[$key], $carry);
        }, $text);
        
        if (count($queryParams) > 0) {
            return $url . '?' . http_build_query($queryParams, '', '&', \PHP_QUERY_RFC3986);
        }
        return $url;
    }
    
}

