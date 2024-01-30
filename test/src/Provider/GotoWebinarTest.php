<?php

namespace League\OAuth2\Client\Test\Provider;

use DalPraS\OAuth2\Client\Provider\GotoWebinar;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Stream;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class GotoWebinarTest extends TestCase
{
    protected GotoWebinar $provider;

    protected function setUp(): void
    {
        $this->provider = new GotoWebinar([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
    }

    public function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function testAuthorizationUrl(): void
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('approval_prompt', $query);
        $this->assertNotNull($this->provider->getState());
    }


    public function testScopes(): void
    {
        $options = ['scope' => [uniqid(),uniqid()]];

        $url = $this->provider->getAuthorizationUrl($options);

        $this->assertStringContainsString(urlencode(implode(',', $options['scope'])), $url);
    }

    public function testGetAuthorizationUrl(): void
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('/oauth/authorize', $uri['path']);
    }

    public function testGetBaseAccessTokenUrl(): void
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('/oauth/token', $uri['path']);
    }

    public function testGetAccessToken(): void
    {
        $testBodyFor = '{"access_token":"mock_access_token", "scope":"repo,gist", "token_type":"bearer"}';
        
        $response = m::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->andReturn(new Stream(fopen('data://text/plain,' . $testBodyFor, 'r')));
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getStatusCode')->andReturn(200);

        $client = m::mock(ClientInterface::class);
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertNull($token->getExpires());
        $this->assertNull($token->getRefreshToken());
        $this->assertNull($token->getResourceOwnerId());
    }

    public function testGotoWebinarEnterpriseDomainUrls(): void
    {
        $this->provider->domain = 'https://my.company.com';
        $testBodyFor = 'access_token=mock_access_token&expires=3600&refresh_token=mock_refresh_token&otherKey={1234}';

        $response = m::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->times(1)->andReturn(new Stream(fopen('data://text/plain,' . $testBodyFor, 'r')));
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'application/x-www-form-urlencoded']);
        $response->shouldReceive('getStatusCode')->andReturn(200);

        $client = m::mock(ClientInterface::class);
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        $this->assertEquals($this->provider->domainAuth . '/oauth/authorize', $this->provider->getBaseAuthorizationUrl());
        $this->assertEquals($this->provider->domainAuth . '/oauth/token', $this->provider->getBaseAccessTokenUrl([]));
        $this->assertEquals($this->provider->domain . '/admin/rest/v1/me', $this->provider->getResourceOwnerDetailsUrl($token));
    }
}
