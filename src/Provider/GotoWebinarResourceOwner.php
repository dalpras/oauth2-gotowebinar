<?php

namespace DalPraS\OAuth2\Client\Provider;

use League\OAuth2\Client\Tool\ArrayAccessorTrait;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class GotoWebinarResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Domain
     *
     * @var string
     */
    protected $domain;

    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * [
     *  [key] => 5242356755789656512,
     *  [accountKey] => 3533365456698298798,
     *  [email] => myname@company.com,
     *  [firstName] => Company,
     *  [lastName] => Training,
     *  [locale] => it_IT,
     *  [adminRoles] => [[0] => MANAGE_SETTINGS, [1] => MANAGE_SEATS, [2] => MANAGE_DEVICE_GROUPS, [3] => MANAGE_GROUPS, [4] => SUPER_USER, [5] => RUN_REPORTS, [6] => MANAGE_USERS],
     *  [accounts] => [[0] => [[key] => 3573263642246205708, [name] => Company, [adminRoles] => [[0] => SUPER_USER, [1] => MANAGE_USERS, [2] => MANAGE_SEATS, [3] => MANAGE_SETTINGS, [4] => MANAGE_GROUPS, [5] => RUN_REPORTS, [6] => MANAGE_DEVICE_GROUPS]]],
     *  [createTime] => 1111113497748,
     *  [products] => [[0] => G2M, [1] => G2W]
     * ]
     *
     * @param array  $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    /**
     * Get resource owner key
     *
     * @return string
     */
    public function getKey() {
        return $this->getValueByKey($this->response, 'key');
    }

    /**
     * Get resource owner Account Key
     *
     * @return string
     */
    public function getAccountKey() {
        return $this->getValueByKey($this->response, 'accountKey');
    }

    /**
     * Get resource owner Email
     *
     * @return string
     */
    public function getEmail() {
        return $this->getValueByKey($this->response, 'email');
    }

    /**
     * Get resource owner FirstName
     *
     * @return string
     */
    public function getFirstName() {
        return $this->getValueByKey($this->response, 'firstName');
    }

    /**
     * Get resource owner LastName
     *
     * @return string
     */
    public function getLastName() {
        return $this->getValueByKey($this->response, 'lastName');
    }

    /**
     * Get resource owner locale
     *
     * @return string
     */
    public function getLocale() {
        return $this->getValueByKey($this->response, 'locale');
    }

    /**
     * Get resource owner creation
     *
     * @return string
     */
    public function getCreateTime() {
        return $this->getValueByKey($this->response, 'createTime');
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
