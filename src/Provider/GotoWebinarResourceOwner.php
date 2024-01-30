<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

/**
 * Classes implementing `ResourceOwnerInterface` may be used to represent
 * the resource owner authenticated with a service provider.
 */
class GotoWebinarResourceOwner implements ResourceOwnerInterface 
{
    use ArrayAccessorTrait;

    public function __construct(
        protected array $response = []
    ) {}

    /**
     * Get resource owner key
     */
    public function getId(): string
    {
        return $this->getKey();
    }

    /**
     * Get resource owner key
     */
    public function getKey(): string
    {
        return $this->getValueByKey($this->response, 'key');
    }

    /**
     * Get resource owner Account Key
     */
    public function getAccountKey(): string
    {
        return $this->getValueByKey($this->response, 'accountKey');
    }

    /**
     * Get resource owner Email
     */
    public function getEmail(): string
    {
        return $this->getValueByKey($this->response, 'email');
    }

    /**
     * Get resource owner FirstName
     */
    public function getFirstName(): string
    {
        return $this->getValueByKey($this->response, 'firstName');
    }

    /**
     * Get resource owner LastName
     */
    public function getLastName(): string
    {
        return $this->getValueByKey($this->response, 'lastName');
    }

    /**
     * Get resource owner locale
     */
    public function getLocale(): string
    {
        return $this->getValueByKey($this->response, 'locale');
    }

    /**
     * Get resource owner creation
     */
    public function getCreateTime(): string
    {
        return $this->getValueByKey($this->response, 'createTime');
    }

    /**
     * Return all of the owner details available as an array.
     */
    public function toArray(): array
    {
        return $this->response;
    }
}
