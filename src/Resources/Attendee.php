<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\Decorators\AccessTokenDecorator;
use DalPraS\OAuth2\Client\Response\ApiResponse;
use Google\Protobuf\Api;

class Attendee extends AuthenticatedResourceAbstract
{
    /**
     * @param array $params
     * @return ApiResponse|mixed
     */
    public function get($params = [], $path = "")
    {
        return new ApiResponse($this->request("get", $path, $params), '', $path, $params);
    }

    /**
     * Get session attendees
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @return ApiResponse
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendees
     */
    public function getSessionAttendees($webinarKey, $sessionKey): ApiResponse
    {
        return $this->get([], "/webinars/{$webinarKey}/sessions/{$sessionKey}/attendees");
    }

    /**
     * Get attendee
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @param int|string $registrantKey
     * @return ApiResponse
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendee
     */
    public function getAttendee($webinarKey, $sessionKey, $registrantKey): ApiResponse
    {
        return $this->get([], "/webinars/{$webinarKey}/sessions/{$sessionKey}/attendees/{$registrantKey}");
    }

    /**
     * Get attendee poll answers
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @param int|string $registrantKey
     * @return ApiResponse
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendeePollAnswers
     */
    public function getAttendeePollAnswers($webinarKey, $sessionKey, $registrantKey): ApiResponse
    {
        return $this->get([], "/webinars/{$webinarKey}/sessions/{$sessionKey}/attendees/{$registrantKey}/polls");
    }

    /**
     * Get attendee questions
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @param int|string $registrantKey
     * @return ApiResponse
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendeeQuestions
     */
    public function getAttendeeQuestions($webinarKey, $sessionKey, $registrantKey): ApiResponse
    {
        return $this->get([], "/webinars/{$webinarKey}/sessions/{$sessionKey}/attendees/{$registrantKey}/questions");
    }

    /**
     * Get attendee survey answers
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @param int|string $registrantKey
     * @return ApiResponse
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAttendeeSurveyAnswers
     */
    public function getAttendeeSurveyAnswers($webinarKey, $sessionKey, $registrantKey): ApiResponse
    {
        return $this->get([], "/webinars/{$webinarKey}/sessions/{$sessionKey}/attendees/{$registrantKey}/surveys");
    }
}
