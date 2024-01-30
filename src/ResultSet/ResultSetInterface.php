<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\ResultSet;

use ArrayAccess;
use ArrayObject;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Serializable;

interface ResultSetInterface extends IteratorAggregate, ArrayAccess, Serializable, Countable, JsonSerializable
{
    /**
     * Return just the data, free of pagination setups and other stuffs.
     */
    public function getData(): ArrayObject;
}
