<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\Decorators\AccessTokenDecorator;
use DalPraS\OAuth2\Client\Response\ApiResponse;

class Webinar extends \DalPraS\OAuth2\Client\Resources\AuthenticatedResourceAbstract
{

    /**
     * @param array $params
     * @return ApiResponse|mixed
     */
    public function get($params = [])
    {
        $path = '/webinars';
        return new ApiResponse($this->request('GET', $path, $params), 'webinars', $path, $params);
    }

    /**
     * Get all webinars.
     *
     * https://api.getgo.com/G2W/rest/v2/account/{accountKey}/webinars?page=0&size=20
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getWebinars
     *
     * @return array
     * @return ApiResponse|mixed
     */
    public function getWebinars(): ApiResponse
    {
        $utcTimeZone = new \DateTimeZone('UTC');
        $params = [
            'fromTime' => (new \DateTime('-3 years', $utcTimeZone))->format('Y-m-d\TH:i:s\Z'),
            'toTime' => (new \DateTime('+3 years', $utcTimeZone))->format('Y-m-d\TH:i:s\Z'),
            'page' => 0,
            'size' => 100
        ];

        return $this->get($params);
    }


    /**
     * Get upcoming webinars.
     *
     * https://api.getgo.com/G2W/rest/v2/account/{accountKey}/webinars?page=0&size=20
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getWebinars
     *
     * @return array
     * @return ApiResponse|mixed
     */
    public function getUpcoming(): ApiResponse
    {
        $utcTimeZone = new \DateTimeZone('UTC');
        $params = [
            'fromTime' => (new \DateTime('now', $utcTimeZone))->format('Y-m-d\TH:i:s\Z'),
            'toTime' => (new \DateTime('+3 year', $utcTimeZone))->format('Y-m-d\TH:i:s\Z'),
            'page' => 0,
            'size' => 100
        ];
        return $this->get($params);
    }


    /**
     * Get webinars in date range.
     *
     * https://api.getgo.com/G2W/rest/v2/account/{accountKey}/webinars?page=0&size=20
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getWebinars
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return array
     *
     * @return array
     * @return ApiResponse|mixed
     */
    public function getPast($startDate, $endDate = null): ApiResponse
    {
        $utcTimeZone = new \DateTimeZone('UTC');
        if ($endDate === null) {
            $endDate = new \DateTime('now', $utcTimeZone);
        }
        $params = [
            'fromTime' => $startDate->setTimezone($utcTimeZone)->format('Y-m-d\TH:i:s\Z'),
            'toTime' => $endDate->format('Y-m-d\TH:i:s\Z'),
            'page' => 0,
            'size' => 100
        ];
        return $this->get($params);
    }

    /**
     * Get info for a single webinar by passing the webinar id or
     * in GotoWebinar's terms webinarKey.
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/getWebinar
     *
     * @param int $webinarKey
     * @return ApiResponse|mixed
     */
    public function getWebinar($webinarKey): ApiResponse
    {
        return new ApiResponse($this->request("get", '/webinars/' . $webinarKey));
    }

    /**
     * Create a new webinar.
     * Return the the WebinarKey.
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/createWebinar
     *
     * @param array $body
     * [
     *      "subject" => "subject",
     *      "description" => "description",
     *      "times" => [[
     *          "startTime" => "2019-02-20T09:00:00Z",
     *          "endTime" => "2019-02-20T10:00:00Z"
     *      ]],
     *      "timeZone" => "Europe/Rome",
     *      "type" => "single_session",
     *      "isPasswordProtected" => false,
     *      "recordingAssetKey" => "string",
     *      "isOndemand" => false,
     *      "experienceType" => "CLASSIC"
     * ]
     *
     * @return array
     * [
     *   "webinarKey": "string"
     * ]
     */
    public function createWebinar(array $body = []): array
    {
        $url = $this->provider->domain . '/G2W/rest/v2/organizers/' . (new AccessTokenDecorator($this->accessToken))->getOrganizerKey() . '/webinars';
        $request = $this->provider->getAuthenticatedRequest('POST', $url, $this->accessToken, [
            'body' => json_encode($body)
        ]);
        return $this->provider->getParsedResponse($request);
    }

    /**
     * Update an existing webinar.
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/updateWebinar
     *
     * @param string $webinarKey
     * @param array $body
     * [
     *     "subject" => "subject",
     *     "description" => "description",
     *     "times" => [[
     *         "startTime" => "2019-01-30T09:00:00Z",
     *         "endTime" => "2019-01-30T10:00:00Z"
     *     ]],
     *     "timeZone" => "Europe/Rome",
     *     "locale" => "it_IT"
     * ]
     *
     * @return array
     */
    public function updateWebinar($webinarKey, array $body = [])
    {
        $url = $this->provider->domain . '/G2W/rest/v2/organizers/' . (new AccessTokenDecorator($this->accessToken))->getOrganizerKey() . '/webinars/' . $webinarKey;
        $request = $this->provider->getAuthenticatedRequest('PUT', $url, $this->accessToken, [
            'body' => json_encode($body)
        ]);
        return $this->provider->getParsedResponse($request);
    }

    /**
     * Delete a webinar.
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}?sendCancellationEmails=false
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/cancelWebinar
     *
     * @param string $webinarKey
     * @return void
     */
    public function deleteWebinar($webinarKey, $sendCancellationEmails = false)
    {
        $url = $this->provider->domain . '/G2W/rest/v2/organizers/' . (new AccessTokenDecorator($this->accessToken))->getOrganizerKey() . '/webinars/' . $webinarKey;
        $url .= '?' . http_build_query(['sendCancellationEmails' => $sendCancellationEmails], null, '&', PHP_QUERY_RFC3986);
        $request = $this->provider->getAuthenticatedRequest('DELETE', $url, $this->accessToken);
        return $this->provider->getParsedResponse($request);
    }

}

