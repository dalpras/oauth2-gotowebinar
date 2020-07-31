<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\Provider\GotoWebinar;
use League\OAuth2\Client\Token\AccessToken;

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
}

