<?php

namespace DalPraS\OAuth2\Client\Provider;

use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;
use League\OAuth2\Client\Tool\RequestFactory;
use League\OAuth2\Client\Grant\GrantFactory;
use GuzzleHttp\Client as HttpClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use DalPraS\OAuth2\Client\Provider\Exception\GotoWebinarProviderException;

/**
 * GotoWebinar Provider.
 *
 * @see https://goto-developer.logmeininc.com/content/gotowebinar-api-reference-v2
 */
class GotoWebinar extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Domain
     *
     * @var string
     */
    public $domain = 'https://api.getgo.com';

    /**
     * Constructs an OAuth 2.0 service provider.
     *
     * @param array $options An array of options to set on this provider.
     *     Options include `clientId`, `clientSecret`, `redirectUri`, and `state`.
     *     Individual providers may introduce more options, as needed.
     * @param array $collaborators An array of collaborators that may be used to
     *     override this provider's default behavior. Collaborators include
     *     `grantFactory`, `requestFactory`, and `httpClient`.
     *     Individual providers may introduce more collaborators, as needed.
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        // We'll let the GuardedPropertyTrait handle mass assignment of incoming
        // options, skipping any blacklisted properties defined in the provider
        $this->fillProperties($options);

        if (empty($collaborators['grantFactory'])) {
            $collaborators['grantFactory'] = new GrantFactory();
        }
        $this->setGrantFactory($collaborators['grantFactory']);

        if (empty($collaborators['requestFactory'])) {
            $collaborators['requestFactory'] = new RequestFactory();
        }
        $this->setRequestFactory($collaborators['requestFactory']);

        if (empty($collaborators['httpClient'])) {
            $client_options = $this->getAllowedClientOptions($options);

            $collaborators['httpClient'] = new HttpClient(
                array_intersect_key($options, array_flip($client_options))
                );
        }
        $this->setHttpClient($collaborators['httpClient']);

        if (empty($collaborators['optionProvider'])) {
            $collaborators['optionProvider'] = new HttpBasicAuthOptionProvider();
        }
        $this->setOptionProvider($collaborators['optionProvider']);
    }

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->domain.'/oauth/v2/authorize';
    }

    /**
     * Get access token url to retrieve token
     *
     * @param  array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->domain.'/oauth/v2/token';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param  AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->domain.'/admin/rest/v1/me';
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [];
    }

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw GotoWebinarProviderException::clientException($response, $data);
        } elseif (isset($data['error'])) {
            throw GotoWebinarProviderException::oauthException($response, $data);
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * [
     *  [key] => 5242356755789656512,
     *  [accountKey] => 3533365456698298798,
     *  [email] => myname@company.com,
     *  [firstName] => Company,
     *  [lastName] => Training,
     *  [locale] => it_IT,
     *  [adminRoles] => [[0] => MANAGE_SETTINGS, [1] => MANAGE_SEATS, [2] => MANAGE_DEVICE_GROUPS, [3] => MANAGE_GROUPS, [4] => SUPER_USER, [5] => RUN_REPORTS, [6] => MANAGE_USERS],
     *  [accounts] => [[0] => [[key] => 3573263642246205708, [name] => Company, [adminRoles] => [[0] => SUPER_USER, [1] => MANAGE_USERS, [2] => MANAGE_SEATS, [3] => MANAGE_SETTINGS, [4] => MANAGE_GROUPS, [5] => RUN_REPORTS, [6] => MANAGE_DEVICE_GROUPS]]],
     *  [createTime] => 1111113497748,
     *  [products] => [[0] => G2M, [1] => G2W]
     * ]
     *
     * @param array $response
     * @param AccessToken $token
     * @return \League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        $user = new GotoWebinarResourceOwner($response);

        return $user->setDomain($this->domain);
    }


}
