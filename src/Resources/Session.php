<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\Decorators\AccessTokenDecorator;

class Session extends AuthenticatedResourceAbstract
{
    /**
     * Get organizer sessions
     *
     * @param \DateTime|null $from
     * @param \DateTime|null $to
     * @param int|null       $page
     * @param int|null       $size
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getOrganizerSessions
     */
    public function getSessions(?\DateTime $from = null, ?\DateTime $to = null, ?int $page = null, ?int $size = null): array
    {
        $utcTimeZone = new \DateTimeZone('UTC');

        $body = [
            'fromTime' => $from
                ? $from->setTimezone($utcTimeZone)->format('Y-m-d\TH:i:s\Z')
                : (new \DateTime('-3 years', $utcTimeZone))->format('Y-m-d\TH:i:s\Z'),
            'toTime'   => $to
                ? $to->setTimezone($utcTimeZone)->format('Y-m-d\TH:i:s\Z')
                : (new \DateTime('-3 years', $utcTimeZone))->format('Y-m-d\TH:i:s\Z'),
            'page'     => $page && $page > -1 ? $page : 0,
            'size'     => $size && $size > 0 ? $size : 100
        ];

        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/sessions?";
        $url .= http_build_query($body, null, '&', \PHP_QUERY_RFC3986);

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request)['_embedded']['sessionInfoResources'];
    }

    /**
     * Get webinar sessions
     *
     * @param int|string $webinarKey
     * @param int|null   $page
     * @param int|null   $size
     * @return array
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getAllSessions
     */
    public function getWebinarSessions($webinarKey, ?int $page = null, ?int $size = null): array
    {
        $body = [
            'page' => $page && $page > -1 ? $page : 0,
            'size' => $size && $size > 0 ? $size : 100
        ];

        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/sessions?";
        $url .= http_build_query($body, null, '&', \PHP_QUERY_RFC3986);

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request)['_embedded']['sessionInfoResources'];
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
    public function getWebinarSession($webinarKey, $sessionKey): array
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/sessions/{$sessionKey}";

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
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
    public function getSessionPerformance($webinarKey, $sessionKey): array
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/sessions/{$sessionKey}/performance";

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
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
    public function getSessionPolls($webinarKey, $sessionKey): array
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/sessions/{$sessionKey}/polls";

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
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
    public function getSessionQuestions($webinarKey, $sessionKey): array
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/sessions/{$sessionKey}/questions";

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
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
    public function getSessionSurveys($webinarKey, $sessionKey): array
    {
        $organizerKey = (new AccessTokenDecorator($this->accessToken))->getOrganizerKey();

        $url = "{$this->provider->domain}/G2W/rest/v2/organizers/{$organizerKey}/webinars/{$webinarKey}/sessions/{$sessionKey}/surveys";

        $request = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);

        return $this->provider->getParsedResponse($request);
    }
}
