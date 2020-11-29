<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\Decorators\AccessTokenDecorator;

class CoOrganizer extends AuthenticatedResourceAbstract
{

    /**
     * Get co-organizers
     *
     * @param int|string $webinarKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getCoorganizers
     */
    public function getCoOrganizers($webinarKey): array
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/coorganizers";

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
    }

    /**
     * Create co-organizers
     *
     * @param int|string $webinarKey
     * @param array      $body
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/createCoorganizers
     */
    public function createCoOrganizers($webinarKey, array $body): array
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/coorganizers";

        $request = $this->provider->getAuthenticatedRequest('POST', $url, $this->accessToken, [
            'body' => json_encode($body)
        ]);

        return $this->provider->getParsedResponse($request);
    }

    /**
     * Delete co-organizer
     *
     * @param int|string $webinarKey
     * @param int|string $coOrganizerKey
     * @param bool       $external
     * @return array|null
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/deleteCoorganizer
     */
    public function deleteCoOrganizer($webinarKey, $coOrganizerKey, bool $external = false)
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/coorganizers/{$coOrganizerKey}";

        if ($external) {
            $url .= "?external=true";
        }

        $request = $this->provider->getAuthenticatedRequest('DELETE', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
    }

    /**
     * Resend invitation
     *
     * @param int|string $webinarKey
     * @param int|string $coOrganizerKey
     * @param bool       $external
     * @return array|null
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/resendCoorganizerInvitation
     */
    public function resendInvitation($webinarKey, $coOrganizerKey, bool $external = false)
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/coorganizers/{$coOrganizerKey}/resendInvitation";

        if ($external) {
            $url .= "?external=true";
        }

        $request = $this->provider->getAuthenticatedRequest('POST', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
    }
}
