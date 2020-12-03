<?php

namespace DalPraS\OAuth2\Client\Decorators;

/**
 * Wrapper of the accessToken for retriving the properties of the accessToken.
 *
 * If you wrap the accessToken with this decorator, you can
 * access other properties of the accessToken that are stored inside the token.
 * @author DalPraS
 *
 * @method string|null getTokenType()
 *      The type of the access token (always "Bearer")
 * @method string|null getOrganizerKey()
 *      GoTo product user organizer key
 * @method string|null getAccountKey()
 *      GoTo product account key (may be blank)
 * @method string|null getAccountType()
 *      GoTo product type “personal” or “corporate” (may be missing or blank)
 * @method string|null getFirstName()
 *      GoTo product user organizer first name (G2M only)
 * @method string|null getLastName()
 *      GoTo product user organizer last name (G2M only)
 * @method string|null getEmail()
 *      GoTo product user organizer email (G2M only)
 * @method string|null getVersion()
 *      The version of the access token
 * @method string getToken()
 *      Returns the access token string of this instance.
 * @method string|null getRefreshToken()
 *      Returns the refresh token, if defined.
 * @method integer|null getExpires()
 *      Returns the expiration timestamp in seconds, if defined.
 * @method boolean hasExpired()
 *      Checks if this token has expired.
 * @method array getValues()
 *      Returns additional vendor values stored in the token.
 * @method string __toString()
 *      Returns a string representation of the access token
 * @method array jsonSerialize()
 *      Returns an array of parameters to serialize when this is serialized with
 */
class AccessTokenDecorator {

    /**
     * @var \League\OAuth2\Client\Token\AccessToken
     */
    private $accessToken;

    /**
     * Getters allowed for properties proxied to accessToken.
     * 
     * @link https://developer.goto.com/guides/HowTos/03_HOW_accessToken/
     * 
     * @var array
     */
    private $tokenProps = [
        // 'access_token',   // OAuth access token
        'token_type',        // The type of the access token (always "Bearer")
        // 'refresh_token',  // Refresh token identifier, valid for 30 days, or until product logout
        'organizer_key',     // GoTo product user organizer key
        'account_key',       // GoTo product account key (may be blank)
        'account_type',      // GoTo product type “personal” or “corporate” (may be missing or blank)
        'firstName',         // GoTo product user organizer first name (G2M only)
        'lastName',          // GoTo product user organizer last name (G2M only)
        'email',             // GoTo product user organizer email (G2M only)
        'version'            // The version of the access token
    ];
    
    /**
     * @param \League\OAuth2\Client\Token\AccessToken $accessToken
     */
    public function __construct(\League\OAuth2\Client\Token\AccessToken $accessToken){
        $this->accessToken = $accessToken;
    }

    /**
     * Proxy to $this->accessToken->getValues() via getters in tokenProps.
     * If methods are not resolved to a property, call proxy to $this->accessToken methods.
     * If the last also fail, raise an exception.
     * 
     * @param string $method
     * @param array $args
     * @throws \Exception
     * @return NULL|mixed
     */
    public function __call($method, $args) {
        if ( substr($method, 0, 3) === 'get' ) {
            $property = substr($this->camelToDashed($method), 4);
            if (in_array($property, $this->tokenProps)) {
                return $this->accessToken->getValues()[$property] ?? NULL;
            }
        }
        
        if (is_callable([$this->accessToken, $method])) {
            return call_user_func_array([$this->accessToken, $method], $args);
        }
        throw new \BadMethodCallException('Wrong method call ' . get_class($this->accessToken) . '::' . $method . ' in ' . __CLASS__ . ' line ' . __LINE__);
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

    private function camelToDashed(string $name) {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $name));
    }    
}
