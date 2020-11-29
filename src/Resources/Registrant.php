<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\Decorators\AccessTokenDecorator;
use DalPraS\OAuth2\Client\Response\ApiResponse;

class Registrant extends \DalPraS\OAuth2\Client\Resources\AuthenticatedResourceAbstract
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
     * Get all registrants for a given webinar.
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/getAllRegistrantsForWebinar
     *
     * @param int $webinarKey
     * @return ApiResponse
     */
    public function getRegistrants($webinarKey): ApiResponse
    {
        return $this->get([], '/webinars/' . $webinarKey . '/registrants');
    }

    /**
     * Get a single registrant for a given webinar.
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants/{registrantKey}
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/getRegistrant
     *
     * @param int $webinarKey
     * @param int $registrantKey
     * @return ApiResponse
     */
    public function getRegistrant($webinarKey, $registrantKey): ApiResponse
    {
        return $this->get([], '/webinars/' . $webinarKey . '/registrants/' . $registrantKey);

    }

    /**
     * Get a single registrant for a given webinar by email.
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/getAllRegistrantsForWebinar
     *
     * @param int $webinarKey
     * @param string $email
     * @return array|NULL
     * [
     *     "firstName" => "string",
     *     "lastName" => "string",
     *     "email" => "string",
     *     "registrantKey" => 0,
     *     "registrationDate" => "2019-01-30T09:00:00Z",
     *     "source" => "string",
     *     "status" => "APPROVED",
     *     "joinUrl" => "string",
     *     "timeZone" => "string",
     *     "phone" => "string",
     *     "state" => "string",
     *     "city" => "string",
     *     "organization" => "string",
     *     "zipCode" => "string",
     *     "numberOfEmployees" => "string",
     *     "industry" => "string",
     *     "jobTitle" => "string",
     *     "purchasingRole" => "string",
     *     "implementationTimeFrame" => "string",
     *     "purchasingTimeFrame" => "string",
     *     "questionsAndComments" => "string",
     *     "employeeCount" => "string",
     *     "country" => "string",
     *     "address" => "string",
     *     "type" => "REGULAR",
     *     "unsubscribed" => true,
     *     "responses" => [[
     *         "answer" => "string",
     *         "question" => "string"
     *     ]]
     * ]
     */
    public function getRegistrantByEmail($webinarKey, $email): ApiResponse
    {
        $registrants = $this->getRegistrants($webinarKey)->getData();
        foreach ($registrants as $registrant) {
            if ($registrant['email'] === $email) {
                return $registrant;
            }
        }
        return [];
    }

    /**
     * Subscribe a registrant for a webinar.
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/createRegistrant
     *
     * @param int $webinarKey
     * @param array $body
     * [
     *     "firstName" => "firstName",
     *     "lastName" => "lastName",
     *     "email" => "email",
     *     "source" => "source",
     *     "address" => "address",
     *     "city" => "city",
     *     "state" => "state",
     *     "zipCode" => "zipCode",
     *     "country" => "country",
     *     "phone" => "phone",
     *     "organization" => "organization",
     *     "jobTitle" => "jobTitle",
     *     "questionsAndComments" => "questionsAndComments",
     *     "industry" => "industry",
     *     "numberOfEmployees" => "numberOfEmployees",
     *     "purchasingTimeFrame" => "purchasingTimeFrame",
     *     "purchasingRole" => "purchasingRole",
     *     "responses" => [[
     *         "questionKey" => 0,
     *         "responseText" => "string",
     *         "answerKey" => 0
     *     ]]
     * ];
     * @return array
     * [
     *   "joinUrl" => "string",
     *   "asset" => true
     *   "registrantKey" => "integer",
     *   "status" => "APPROVED",
     * ]
     */
    public function createRegistrant($webinarKey, $body): array
    {
        $url = $this->provider->domain . '/G2W/rest/v2/organizers/' . (new AccessTokenDecorator($this->accessToken))->getOrganizerKey() . '/webinars/' . $webinarKey . '/registrants';
        $request = $this->provider->getAuthenticatedRequest('POST', $url, $this->accessToken, [
            'headers' => [
                'Accept' => 'application/vnd.citrix.g2wapi-v1.1+json',
            ],
            'body' => json_encode($body)
        ]);
        return $this->provider->getParsedResponse($request);
    }

    /**
     * Unsubscribe a registrant from a webinar.
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants/{registrantKey}
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/deleteRegistrant
     *
     * @param int $webinarKey
     * @param int $registrantKey
     * @return array
     */
    public function deleteRegistrant($webinarKey, $registrantKey)
    {
        $url = $this->provider->domain . '/G2W/rest/v2/organizers/' . (new AccessTokenDecorator($this->accessToken))->getOrganizerKey() . '/webinars/' . $webinarKey . '/registrants/' . $registrantKey;
        $request = $this->provider->getAuthenticatedRequest('DELETE', $url, $this->accessToken);
        return $this->provider->getParsedResponse($request);
    }


}
