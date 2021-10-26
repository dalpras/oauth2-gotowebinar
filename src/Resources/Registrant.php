<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\ResultSet\SimpleResultSet;

class Registrant extends \DalPraS\OAuth2\Client\Resources\AuthenticatedResourceAbstract
{
    /**
     * Get all registrants for a given webinar.
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/getAllRegistrantsForWebinar
     *
     * @param string $webinarKey
     */
    public function getRegistrants(string $webinarKey): SimpleResultSet
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/registrants', [
            'webinarKey' => $webinarKey,
        ]);
        $request  = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * Get a single registrant for a given webinar.
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants/{registrantKey}
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/getRegistrant
     *
     * @param string $webinarKey
     * @param string $registrantKey
     */
    public function getRegistrant(string $webinarKey, string $registrantKey): SimpleResultSet
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/registrants/{registrantKey}', [
            'webinarKey' => $webinarKey,
            'registrantKey' => $registrantKey
        ]);
        $request  = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * Get a single registrant for a given webinar by email.
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/getAllRegistrantsForWebinar
     *
     * @param string $webinarKey
     * @param string $email
     * @return array|NULL
     */
    public function getRegistrantByEmail(string $webinarKey, string $email): SimpleResultSet
    {
        $registrants = $this->getRegistrants($webinarKey);
        foreach ($registrants as $registrant) {
            if ($registrant['email'] === $email) {
                return new SimpleResultSet($registrant);
            }
        }
        return new SimpleResultSet();
    }

    /**
     * Register an attendee for a scheduled webinar. Requires the organizer and a scheduled webinar.
     * Please note that you must provide all required fields including custom fields defined during the webinar creation. 
     * Use the API call 'Get registration fields' to get a list of all fields, if they are required, and their possible values. 
     * 
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/createRegistrant
     *
     * @param string $webinarKey
     * @param array $body 
     * @param bool $resendConfirmation
     *      Indicates whether the confirmation email should be resent when a registrant is re-registered
     */
    public function createRegistrant(string $webinarKey, array $body, bool $resendConfirmation = false): SimpleResultSet
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/registrants', [
            'webinarKey' => $webinarKey,
        ], [
            'resendConfirmation' => $resendConfirmation ? 'true' : 'false'
        ]);
        $request  = $this->provider->getAuthenticatedRequest('POST', $url, $this->accessToken, [
            'headers' => [
                'Accept' => 'application/vnd.citrix.g2wapi-v1.1+json',
            ],
            'body' => json_encode($body)
        ]);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * Retrieve required, optional registration, and custom questions for a specified webinar.
     * 
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants/fields
     * 
     * @link https://developer.goto.com/GoToWebinarV2#operation/getRegistrationFields
     */
    public function getRegistrationFields(string $webinarKey, string $organizerKey): SimpleResultSet 
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/registrants/fields',[
            'webinarKey' => $webinarKey,
            'organizerKey' => $organizerKey
        ]);
        $request  = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }
    
    /**
     * Unsubscribe a registrant from a webinar.
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars/{webinarKey}/registrants/{registrantKey}
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/deleteRegistrant
     *
     * @param string $webinarKey
     * @param string $registrantKey
     * @return array
     */
    public function deleteRegistrant(string $webinarKey, string $registrantKey): SimpleResultSet
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}/registrants/{registrantKey}', [
            'webinarKey' => $webinarKey,
            'registrantKey' => $registrantKey
        ]);
        $request  = $this->provider->getAuthenticatedRequest('DELETE', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }
}
