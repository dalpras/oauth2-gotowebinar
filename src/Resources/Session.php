<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\Response\ApiResponse;

class Session extends AuthenticatedResourceAbstract
{
    /**
     * @param array $params
     * @return ApiResponse|mixed
     */
    public function get($params = [], $path = "/sessions")
    {
        return new ApiResponse($this->request("get", $path, $params), 'sessionInfoResources', $path, $params);
    }


    /**
     * Get organizer sessions
     *
     * @param \DateTime|null $from
     * @param \DateTime|null $to
     * @param int|null $page
     * @param int|null $size
     * @return ApiResponse
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getOrganizerSessions
     */
    public function getSessions(?\DateTime $from = null, ?\DateTime $to = null, ?int $page = null, ?int $size = null): ApiResponse
    {
        $utcTimeZone = new \DateTimeZone('UTC');

        $body = [
            'fromTime' => $from
                ? $from->setTimezone($utcTimeZone)->format('Y-m-d\TH:i:s\Z')
                : (new \DateTime('-3 years', $utcTimeZone))->format('Y-m-d\TH:i:s\Z'),
            'toTime' => $to
                ? $to->setTimezone($utcTimeZone)->format('Y-m-d\TH:i:s\Z')
                : (new \DateTime('-3 years', $utcTimeZone))->format('Y-m-d\TH:i:s\Z'),
            'page' => $page && $page > -1 ? $page : 0,
            'size' => $size && $size > 0 ? $size : 100
        ];


        return $this->get($body);
    }

    /**
     * Get webinar sessions
     *
     * @param int|string $webinarKey
     * @param int|null $page
     * @param int|null $size
     * @return ApiResponse
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAllSessions
     */
    public function getWebinarSessions($webinarKey, ?int $page = null, ?int $size = null): ApiResponse
    {
        $body = [
            'page' => $page && $page > -1 ? $page : 0,
            'size' => $size && $size > 0 ? $size : 100
        ];

        return $this->get($body, "/webinars/{$webinarKey}/sessions");
    }

    /**
     * Get webinar session
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getWebinarSession
     */
    public function getWebinarSession($webinarKey, $sessionKey): ApiResponse
    {
        return $this->get([], "/webinars/{$webinarKey}/sessions/{$sessionKey}");
    }

    /**
     * Get session performance
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getPerformance
     */
    public function getSessionPerformance($webinarKey, $sessionKey): ApiResponse
    {
        return $this->get([], "/webinars/{$webinarKey}/sessions/{$sessionKey}/performance");
    }

    /**
     * Get session polls
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getPolls
     */
    public function getSessionPolls($webinarKey, $sessionKey): ApiResponse
    {
        return $this->get([], "/webinars/{$webinarKey}/sessions/{$sessionKey}/polls");
    }

    /**
     * Get session questions
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getQuestions
     */
    public function getSessionQuestions($webinarKey, $sessionKey): ApiResponse
    {
        return $this->get([], "/webinars/{$webinarKey}/sessions/{$sessionKey}/questions");
    }

    /**
     * Get session surveys
     *
     * @param int|string $webinarKey
     * @param int|string $sessionKey
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getSurveys
     */
    public function getSessionSurveys($webinarKey, $sessionKey): ApiResponse
    {
        return $this->get([], "/webinars/{$webinarKey}/sessions/{$sessionKey}/surveys");
    }
}
