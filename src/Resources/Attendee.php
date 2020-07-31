<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\Decorators\AccessTokenDecorator;

class Attendee extends AuthenticatedResourceAbstract
{
    /**
     * Get session attendees
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendees
     */
    public function getSessionAttendees($webinarKey, $sessionKey): array
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/sessions/{$sessionKey}/attendees";

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
    }

    /**
     * Get attendee
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @param int|string $registrantKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendee
     */
    public function getAttendee($webinarKey, $sessionKey, $registrantKey): array
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/sessions/{$sessionKey}/attendees/{$registrantKey}";

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
    }

    /**
     * Get attendee poll answers
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @param int|string $registrantKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendeePollAnswers
     */
    public function getAttendeePollAnswers($webinarKey, $sessionKey, $registrantKey): array
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/sessions/{$sessionKey}/attendees/{$registrantKey}/polls";

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
    }

    /**
     * Get attendee questions
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @param int|string $registrantKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendeeQuestions
     */
    public function getAttendeeQuestions($webinarKey, $sessionKey, $registrantKey): array
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/sessions/{$sessionKey}/attendees/{$registrantKey}/questions";

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
    }

    /**
     * Get attendee survey answers
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @param int|string $registrantKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendeeSurveyAnswers
     */
    public function getAttendeeSurveyAnswers($webinarKey, $sessionKey, $registrantKey): array
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/sessions/{$sessionKey}/attendees/{$registrantKey}/surveys";

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
    }
}
