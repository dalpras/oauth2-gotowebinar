<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\Provider;

use DalPraS\OAuth2\Client\Provider\Exception\GotoWebinarProviderException;
use GuzzleHttp\Client as HttpClient;
use League\OAuth2\Client\Grant\GrantFactory;
use League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use League\OAuth2\Client\Tool\RequestFactory;
use Psr\Http\Message\ResponseInterface;

/**
 * GotoWebinar Provider.
 *
 * @see https://goto-developer.logmeininc.com/content/gotowebinar-api-reference-v2
 */
class GotoWebinar extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Logmeininc authentication host
     */
    public string $domainAuth = 'https://authentication.logmeininc.com';

    /**
     * Scim authenticazion host
     */
    public string $domain = 'https://api.getgo.com';

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
     */
    public function getBaseAuthorizationUrl(): string
    {
        return $this->domainAuth . '/oauth/authorize';
    }

    /**
     * Get access token url to retrieve token
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->domainAuth . '/oauth/token';
    }

    /**
     * Get provider url to fetch user details
     * 
     * @link https://developer.goto.com/Scim#operation/getMe
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->domain . '/admin/rest/v1/me';
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     */
    protected function getDefaultScopes(): array
    {
        return [];
    }

    /**
     * Check a provider response for errors.
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() >= 400) {
            throw GotoWebinarProviderException::clientException($response, $data);
        } elseif (isset($data['error'])) {
            throw GotoWebinarProviderException::oauthException($response, $data);
        }
    }

    /**
     * Generate a user object from a successful user details request.
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        $user = new GotoWebinarResourceOwner($response);
        return $user;
    }
}
