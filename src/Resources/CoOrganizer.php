<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\ResultSet\SimpleResultSet;
use DalPraS\OAuth2\Client\ResultSet\ResultSetInterface;

class CoOrganizer extends AuthenticatedResourceAbstract
{
    /**
     * Get co-organizers
     *
     * @param string $webinarKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getCoorganizers
     */
    public function getCoOrganizers(string $webinarKey): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/coorganizers', [
            'webinarKey' => $webinarKey,
        ]);
        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * Create co-organizers
     *
     * @param string $webinarKey
     * @param array $body
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/createCoorganizers
     */
    public function createCoOrganizers(string $webinarKey, array $body): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/coorganizers', [
            'webinarKey' => $webinarKey
        ]);
        $request = $this->provider->getAuthenticatedRequest('POST', $url, $this->accessToken, [
            'body' => json_encode($body)
        ]);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
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
    public function deleteCoOrganizer(string $webinarKey, string $coOrganizerKey, bool $external = false): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/coorganizers/{coorganizerKey}', [
            'webinarKey' => $webinarKey,
            'coorganizerKey' => $coOrganizerKey
        ], [
            'external' => $external ? 'true' : 'false'
        ]);

        $request = $this->provider->getAuthenticatedRequest('DELETE', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * Resend invitation
     *
     * @param string $webinarKey
     * @param string $coOrganizerKey
     * @param bool $external
     * @return array|null
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/resendCoorganizerInvitation
     */
    public function resendInvitation(string $webinarKey, string $coOrganizerKey, bool $external = false): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/coorganizers/{coorganizerKey}/resendInvitation', [
            'webinarKey' => $webinarKey,
            'coorganizerKey' => $coOrganizerKey
        ], [
            'external' => $external ? 'true' : 'false'
        ]);
        $request = $this->provider->getAuthenticatedRequest('POST', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }
}
