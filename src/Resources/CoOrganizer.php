<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\ResultSet\SimpleResultSet;

class CoOrganizer extends AuthenticatedResourceAbstract
{
    /**
     * Get co-organizers
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getCoorganizers
     */
    public function getCoOrganizers(string $webinarKey): SimpleResultSet
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
     * @link https://developer.goto.com/GoToWebinarV2/#operation/createCoorganizers
     */
    public function createCoOrganizers(string $webinarKey, array $body): SimpleResultSet
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
     * @link https://developer.goto.com/GoToWebinarV2/#operation/deleteCoorganizer
     */
    public function deleteCoOrganizer(string $webinarKey, string $coOrganizerKey, bool $external = false): SimpleResultSet
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/coorganizers/{coorganizerKey}', [
            'webinarKey'     => $webinarKey,
            'coorganizerKey' => $coOrganizerKey
        ], [
            'external' => $external ? 'true' : 'false'
        ]);

        $request = $this->provider->getAuthenticatedRequest('DELETE', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * Resends an invitation email to the specified co-organizer. For external co-organizers 
     * (individuals who do not have a shared GoToWebinar account), set the URL parameter 'external' = true.
     * 
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/coorganizers/{coorganizerKey}/resendInvitation
     * @link https://developer.goto.com/GoToWebinarV2/#operation/resendCoorganizerInvitation
     */
    public function resendInvitation(string $webinarKey, string $coOrganizerKey, bool $external = false): SimpleResultSet
    {
        return $this->resendCoOrganizerInvitation($webinarKey, $coOrganizerKey, $external);
    }

    /**
     * Resends an invitation email to the specified co-organizer. For external co-organizers 
     * (individuals who do not have a shared GoToWebinar account), set the URL parameter 'external' = true.
     * 
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/coorganizers/{coorganizerKey}/resendInvitation
     * 
     * @link https://developer.goto.com/GoToWebinarV2/#operation/resendCoorganizerInvitation
     */
    public function resendCoOrganizerInvitation(string $webinarKey, string $coOrganizerKey, bool $external = false): SimpleResultSet
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
