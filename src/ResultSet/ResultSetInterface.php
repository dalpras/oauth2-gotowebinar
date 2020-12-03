<?php

namespace DalPraS\OAuth2\Client\ResultSet;

interface ResultSetInterface extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{

    /**
     * Return just the data, free of pagination setups and other stuffs.
     */
    public function getData(): \ArrayObject;
}
