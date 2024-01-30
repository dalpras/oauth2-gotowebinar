<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\ResultSet;

use ArrayObject;

class SimpleResultSet extends ArrayObject implements ResultSetInterface
{

    /**
     * Initialize ArrayObject with parsed response from the GoToWebinar 
     */
    public function __construct(string|array $response) 
    {
        parent::__construct( is_array($response) ? $response : [] );
    }
    
    /**
     * {@inheritDoc}
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }
    
    /**
     * {@inheritDoc}
     * @see \DalPraS\OAuth2\Client\ResultSet\ResultSetInterface::getData()
     */
    public function getData(): ArrayObject
    {
        return new ArrayObject([]);
    }
}
