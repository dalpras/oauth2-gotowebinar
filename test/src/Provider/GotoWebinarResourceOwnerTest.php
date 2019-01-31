<?php namespace League\OAuth2\Client\Test\Provider;

use Mockery as m;

class GotoWebinarResourceOwnerTest extends \PHPUnit_Framework_TestCase
{
    public function testUrlIsNullWithoutDomainOrNickname()
    {
        $user = new \League\OAuth2\Client\Provider\GotoWebinarResourceOwner;

        $url = $user->getUrl();

        $this->assertNull($url);
    }

    public function testUrlIsDomainWithoutNickname()
    {
        $domain = uniqid();
        $user = new \League\OAuth2\Client\Provider\GotoWebinarResourceOwner;
        $user->setDomain($domain);

        $url = $user->getUrl();

        $this->assertEquals($domain, $url);
    }

    public function testUrlIsNicknameWithoutDomain()
    {
        $nickname = uniqid();
        $user = new \League\OAuth2\Client\Provider\GotoWebinarResourceOwner(['login' => $nickname]);

        $url = $user->getUrl();

        $this->assertEquals($nickname, $url);
    }
}
