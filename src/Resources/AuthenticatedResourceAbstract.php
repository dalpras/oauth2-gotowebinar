<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\Provider\GotoWebinar;
use League\OAuth2\Client\Token\AccessToken;

abstract class AuthenticatedResourceAbstract 
{
    public function __construct(
        protected GotoWebinar $provider, 
        protected AccessToken $accessToken
    ) { }
    
    /**
     * Replace all the named keys {name} in $text with the respective value from $attribs.
     * If $queryParams are present, they will be added to the url as a query.
     */
    protected function getRequestUrl(string $text, array $attribs = [], array $queryParams = []): string
    {
        /** @var \DalPraS\OAuth2\Client\Provider\GotoWebinarResourceOwner $owner */
        $owner = $this->provider->getResourceOwner($this->accessToken);

        $attribs = array_replace([
                'organizerKey' => $owner->getKey(),
                'accountKey'   => $owner->getAccountKey(),
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

