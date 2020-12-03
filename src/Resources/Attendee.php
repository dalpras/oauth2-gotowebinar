<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\ResultSet\SimpleResultSet;
use DalPraS\OAuth2\Client\ResultSet\ResultSetInterface;

class Attendee extends AuthenticatedResourceAbstract
{
    /**
     * Get session attendees
     *
     * @param string $webinarKey
     * @param string $sessionKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendees
     */
    public function getSessionAttendees(string $webinarKey, string $sessionKey): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/sessions/{sessionKey}/attendees', [
            'webinarKey' => $webinarKey,
            'sessionKey' => $sessionKey
        ]);
        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * Get attendee
     *
     * @param string $webinarKey
     * @param string $sessionKey
     * @param string $registrantKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendee
     */
    public function getAttendee(string $webinarKey, string $sessionKey, string $registrantKey): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/sessions/{sessionKey}/attendees/{registrantKey}', [
            'webinarKey' => $webinarKey,
            'sessionKey' => $sessionKey,
            'registrantKey' => $registrantKey
        ]);
        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * Get attendee poll answers
     *
     * @param string $webinarKey
     * @param string $sessionKey
     * @param string $registrantKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendeePollAnswers
     */
    public function getAttendeePollAnswers(string $webinarKey, string $sessionKey, string $registrantKey): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/sessions/{sessionKey}/attendees/{registrantKey}/polls', [
            'webinarKey' => $webinarKey,
            'sessionKey' => $sessionKey,
            'registrantKey' => $registrantKey
        ]);
        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * Get attendee questions
     *
     * @param string $webinarKey
     * @param string $sessionKey
     * @param string $registrantKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendeeQuestions
     */
    public function getAttendeeQuestions(string $webinarKey, string $sessionKey, string $registrantKey): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/sessions/{sessionKey}/attendees/{registrantKey}/questions', [
            'webinarKey' => $webinarKey,
            'sessionKey' => $sessionKey,
            'registrantKey' => $registrantKey
        ]);
        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * Get attendee survey answers
     *
     * @param string $webinarKey
     * @param string $sessionKey
     * @param string $registrantKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendeeSurveyAnswers
     */
    public function getAttendeeSurveyAnswers(string $webinarKey, string $sessionKey, string $registrantKey): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/sessions/{sessionKey}/attendees/{registrantKey}/surveys', [
            'webinarKey' => $webinarKey,
            'sessionKey' => $sessionKey,
            'registrantKey' => $registrantKey
        ]);
        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }
}
