<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\Decorators\AccessTokenDecorator;

class Registrant extends \DalPraS\OAuth2\Client\Resources\AuthenticatedResourceAbstract {

    /**
     * Get all registrants for a given webinar.
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants
     *
     * @param int $webinarKey
     * @return array
     * [
     *   [
     *     "lastName" => "string",
     *     "email" => "string",
     *     "firstName" => "string",
     *     "registrantKey" => 0,
     *     "registrationDate" => "2019-01-30T09:00:00Z",
     *     "status" => "APPROVED",
     *     "joinUrl" => "string",
     *     "timeZone" => "string"
     *   ]
     * ]
     */
    public function getRegistrants($webinarKey):array {
        $url = $this->provider->domain . '/G2W/rest/v2/organizers/' . (new AccessTokenDecorator($this->accessToken))->getOrganizerKey() . '/webinars/' . $webinarKey . '/registrants';
        $request  = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return $this->provider->getParsedResponse($request);
    }

    /**
     * Get a single registrant for a given webinar.
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants/{registrantKey}
     *
     * @param int $webinarKey
     * @param int $registrantKey
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
    public function getRegistrant($webinarKey, $registrantKey):array {
        $url = $this->provider->domain . '/G2W/rest/v2/organizers/' . (new AccessTokenDecorator($this->accessToken))->getOrganizerKey() . '/webinars/' . $webinarKey . '/registrants/' . $registrantKey;
        $request  = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return $this->provider->getParsedResponse($request);
    }

    /**
     * Get a single registrant for a given webinar by email.
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
    public function getRegistrantByEmail($webinarKey, $email):array {
        $registrants = $this->getRegistrants($webinarKey);
        foreach ($registrants as $registrant) {
            if ($registrant['email'] === $email) {
                return $registrant;
            }
        }
        return null;
    }

    /**
     * Subscribe a registrant for a webinar.
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants
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
    public function createRegistrant($webinarKey, $body) : array {
        $url = $this->provider->domain . '/G2W/rest/v2/organizers/' . (new AccessTokenDecorator($this->accessToken))->getOrganizerKey() . '/webinars/' . $webinarKey . '/registrants';
        $request  = $this->provider->getAuthenticatedRequest('POST', $url, $this->accessToken, [
            'body' => json_encode($body)
        ]);
        return $this->provider->getParsedResponse($request);
    }

    /**
     * Unsubscribe a registrant from a webinar.
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants/{registrantKey}
     *
     * @param int $webinarKey
     * @param int $registrantKey
     * @return array
     */
    public function deleteRegistrant($webinarKey, $registrantKey) {
        $url = $this->provider->domain . '/G2W/rest/v2/organizers/' . (new AccessTokenDecorator($this->accessToken))->getOrganizerKey() . '/webinars/' . $webinarKey . '/registrants/' . $registrantKey;
        $request  = $this->provider->getAuthenticatedRequest('DELETE', $url, $this->accessToken);
        return $this->provider->getParsedResponse($request);
    }


}