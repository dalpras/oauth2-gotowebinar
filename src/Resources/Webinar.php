<?php

namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\ResultSet\ResultSetInterface;
use DalPraS\OAuth2\Client\ResultSet\PageResultSet;
use DalPraS\OAuth2\Client\ResultSet\SimpleResultSet;

class Webinar extends \DalPraS\OAuth2\Client\Resources\AuthenticatedResourceAbstract
{
    /**
     * Get webinars by Account.
     *
     * https://api.getgo.com/G2W/rest/v2/accounts/{accountKey}/webinars
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/getAllAccountWebinars
     */
    public function getWebinarsByAccount(?\DateTime $from = null, ?\DateTime $to = null, int $page = 0, int $size = 100): ResultSetInterface
    {
        $utcTimeZone = new \DateTimeZone('UTC');
        $query      = [
            'fromTime' => ($from ?? new \DateTime('-3 years'))->setTimezone($utcTimeZone)->format('Y-m-d\TH:i:s\Z'),
            'toTime'   => ($to ?? new \DateTime('+3 years'))->setTimezone($utcTimeZone)->format('Y-m-d\TH:i:s\Z'),
            'page'     => $page,
            'size'     => $size
        ];
        $url = $this->getRequestUrl('/accounts/{accountKey}/webinars', [], $query);
        $request  = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new PageResultSet($this->provider->getParsedResponse($request), 'webinars');
    }
    
    /**
     * Get webinars by Organizer.
     *
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/getWebinars
     */
    public function getWebinarsByOrganizer(?\DateTime $from = null, ?\DateTime $to = null, int $page = 0, int $size = 100): ResultSetInterface
    {
        $utcTimeZone = new \DateTimeZone('UTC');
        $query      = [
            'fromTime' => ($from ?? new \DateTime('-3 years'))->setTimezone($utcTimeZone)->format('Y-m-d\TH:i:s\Z'),
            'toTime'   => ($to ?? new \DateTime('+3 years'))->setTimezone($utcTimeZone)->format('Y-m-d\TH:i:s\Z'),
            'page'     => $page,
            'size'     => $size
        ];
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars', [], $query);
        $request  = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new PageResultSet($this->provider->getParsedResponse($request), 'webinars');
    }

    /**
     * Get webinars by Organizer.
     *
     * https://api.getgo.com/G2W/rest/v2/organizers/{organizerKey}/webinars
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/getWebinars
     */
    public function getWebinars(?\DateTime $from = null, ?\DateTime $to = null, int $page = 0, int $size = 100): ResultSetInterface
    {
        return $this->getWebinarsByOrganizer($from, $to, $page, $size);
    }

    /**
     * Get upcoming webinars.
     *
     * @deprecated use getWebinarsByOrganizer
     *
     * https://api.getgo.com/G2W/rest/v2/account/{accountKey}/webinars?page=0&size=20
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getWebinars
     */
    public function getUpcoming(?\DateTime $from = null, ?\DateTime $to = null, int $page = 0, int $size = 100): ResultSetInterface
    {
        return $this->getWebinarsByOrganizer($from ?? new \DateTime('now'), $to ?? new \DateTime('+3 years'), $page, $size);
    }

    /**
     * Get webinars in date range.
     *
     * @deprecated use getWebinarsByOrganizer
     *
     * https://api.getgo.com/G2W/rest/v2/account/{accountKey}/webinars?page=0&size=20
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getWebinars

     */
    public function getPast(?\DateTime $from = null, ?\DateTime $to = null, int $page = 0, int $size = 100): ResultSetInterface
    {
        return $this->getWebinarsByOrganizer($from ?? new \DateTime('-3 years'), $to ?? new \DateTime('now'), $page, $size);
    }

    /**
     * Get info for a single webinar by passing the webinar id or
     * in GotoWebinar's terms webinarKey.
     *
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getWebinar
     *
     * @param string $webinarKey
     */
    public function getWebinar(string $webinarKey): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}', ['webinarKey' => $webinarKey]);
        $request  = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * Create a new webinar.
     * Return the the WebinarKey.
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/createWebinar
     */
    public function createWebinar(array $body = []): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars');
        $request  = $this->provider->getAuthenticatedRequest('POST', $url, $this->accessToken, [
            'body' => json_encode($body)
        ]);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * Update an existing webinar.
     *
     * @link https://developer.goto.com/GoToWebinarV2#operation/updateWebinar
     *
     * @param string $webinarKey
     * @param array $body
     */
    public function updateWebinar(string $webinarKey, array $body = []): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}', ['webinarKey' => $webinarKey]);
        $request  = $this->provider->getAuthenticatedRequest('PUT', $url, $this->accessToken, [
            'body' => json_encode($body)
        ]);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
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
    public function deleteWebinar($webinarKey, $sendEmail = false): ResultSetInterface
    {
        $url = $this->getRequestUrl('/organizers/{organizerKey}/webinars/{webinarKey}', ['webinarKey' => $webinarKey], ['sendCancellationEmails' => $sendEmail]);
        $request  = $this->provider->getAuthenticatedRequest('DELETE', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }
}
