<?php
namespace DalPraS\OAuth2\Client\Resources;

use DalPraS\OAuth2\Client\ResultSet\SimpleResultSet;
use DalPraS\OAuth2\Client\ResultSet\PageResultSet;
use DalPraS\OAuth2\Client\Helper\DateUtcHelper;

class Webhook extends \DalPraS\OAuth2\Client\Resources\AuthenticatedResourceAbstract
{
    
    /**
     * A new webhook will be created with the provided callback URL. 
     * Callback URL should be a secure (https://) URL. 
     * Callback URL will be validated by making a GET request.
     * 
     * https://api.getgo.com/G2W/rest/v2/webhooks/secretkey
     * 
     * @param string $validFrom The secret key will be activated at the given date
     * @link https://developer.goto.com/GoToWebinarV2#operation/createSecretKey
     */
    public function createSecretKey(?\DateTime $validFrom = null): SimpleResultSet
    {
        $body = [
            'validFrom' => DateUtcHelper::date2utc($validFrom ?? new \DateTime('now'))
        ];
        $url = $this->getRequestUrl('/webhooks/secretkey');
        $request  = $this->provider->getAuthenticatedRequest('POST', $url, $this->accessToken, [
            'body' => json_encode($body)
        ]);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }

    /**
     * A new webhook will be created with the provided callback url. 
     * - callbackUrl: Callback url should be a https url. Callback url will be validated by making a GET request. It should return 200 OK.
     * - eventName: Supported eventNames are registrant.added, registrant.joined, webinar.created, webinar.updated
     * - eventVersion: Version of event being subscribed for. Supported eventVersion is 1.0.0
     * - product: Value "g2w"
     * 
     * https://api.getgo.com/G2W/rest/v2/webhooks
     * 
     * @link https://developer.goto.com/GoToWebinarV2/#operation/createWebhooks
     * 
     * @param array $body Webhooks object to create
     *      [["callbackUrl": "string", "eventName": "string", "eventVersion": "string", "product": "g2w"]]
     */
    public function createWebhooks(array $body = []): PageResultSet
    {
        $url = $this->getRequestUrl('/webhooks');
        $request  = $this->provider->getAuthenticatedRequest('POST', $url, $this->accessToken, [
            'body' => json_encode($body)
        ]);
        return new PageResultSet($this->provider->getParsedResponse($request), 'webhooks');
    }
    
    /**
     * Callback url and state of webhooks can be updated using this API. 
     * - callbackUrl: Callback url should be a https url. Callback url will be validated by making a GET request. It should return 200 OK.
     * - webhookKey: The unique identifier for the webhook
     * - state:  State of the webhook "INACTIVE" or "ACTIVE"
     * 
     * https://api.getgo.com/G2W/rest/v2/webhooks
     * 
     * @link https://developer.goto.com/GoToWebinarV2/#operation/updateWebhooks
     * 
     * @param array $body Webhooks object to update 
     *      [["callbackUrl": "string", "webhookKey": "string", "state": "INACTIVE"]]
     */
    public function updateWebhooks(array $body = []): SimpleResultSet 
    {
        $url = $this->getRequestUrl('/webhooks');
        $request  = $this->provider->getAuthenticatedRequest('PUT', $url, $this->accessToken, [
            'body' => json_encode($body)
        ]);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }
    
    
    /**
     * Get all the webhooks created.
     * 
     * https://api.getgo.com/G2W/rest/v2/webhooks
     * 
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getWebhooks
     */
    public function getWebhooks(): PageResultSet 
    {
        $query = ['product' => 'g2w'];
        $url = $this->getRequestUrl('/webhooks', [], $query);
        $request  = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new PageResultSet($this->provider->getParsedResponse($request), 'webhooks');
    }
    
    /**
     * Get Webhook data.
     * 
     * https://api.getgo.com/G2W/rest/v2/webhooks/{webhookKey}
     * 
     * @link https://developer.goto.com/GoToWebinarV2/#operation/getWebhook
     * 
     * @param string $webhookKey
     */
    public function getWebhook(string $webhookKey): SimpleResultSet 
    {
        $url = $this->getRequestUrl('/webhooks/{webhookKey}', ['webhookKey' => $webhookKey]);
        $request  = $this->provider->getAuthenticatedRequest('GET', $url, $this->accessToken);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }
    
    /**
     * Delete the webhooks passed.
     * 
     * https://api.getgo.com/G2W/rest/v2/webhooks
     * 
     * @link https://developer.goto.com/GoToWebinarV2/#operation/deleteWebhooks
     * 
     * @param array $body List of webhookKeys to delete
     */
    public function deleteWebhooks(array $body = []): SimpleResultSet
    {
        $url = $this->getRequestUrl('/webhooks');
        $request  = $this->provider->getAuthenticatedRequest('DELETE', $url, $this->accessToken, [
            'body' => json_encode($body)
        ]);
        return new SimpleResultSet($this->provider->getParsedResponse($request));
    }
}



