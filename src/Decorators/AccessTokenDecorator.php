<?php

namespace DalPraS\OAuth2\Client\Decorators;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use League\OAuth2\Server\Exception\OAuthServerException;

class AccessTokenDecorator {

    /**
     * @var \League\OAuth2\Client\Token\AccessToken
     */
    private $accessToken;

    /**
     * @param \League\OAuth2\Client\Token\AccessToken $accessToken
     */
    public function __construct(\League\OAuth2\Client\Token\AccessToken $accessToken){
        $this->accessToken = $accessToken;
    }

    public function __call($method, $args) {
        if (is_callable([$this->accessToken, $method])) {
            return call_user_func_array([$this->accessToken, $method], $args);
        }
        throw new \Exception('Undefined method - ' . get_class($this->accessToken) . '::' . $method);
    }

    public function __get($property) {
        if (property_exists($this->accessToken, $property)) {
            return $this->accessToken->$property;
        }
    }

    public function __set($property, $value) {
        $this->accessToken->$property = $value;
        return $this;
    }

    public function getOrganizerKey() {
        return isset($this->accessToken->getValues()['organizer_key']) ? $this->accessToken->getValues()['organizer_key'] : NULL;
    }

    public function getAccountKey() {
        return isset($this->accessToken->getValues()['account_key']) ? $this->accessToken->getValues()['account_key'] : NULL;
    }

    public function getFirstName() {
        return isset($this->accessToken->getValues()['firstName']) ? $this->accessToken->getValues()['firstName'] : NULL;
    }

    public function getLastName() {
        return isset($this->accessToken->getValues()['lastName']) ? $this->accessToken->getValues()['lastName'] : NULL;
    }

    public function getEmail() {
        return isset($this->accessToken->getValues()['email']) ? $this->accessToken->getValues()['email'] : NULL;
    }

    public function getVersion() {
        return isset($this->accessToken->getValues()['version']) ? $this->accessToken->getValues()['version'] : NULL;
    }
}
