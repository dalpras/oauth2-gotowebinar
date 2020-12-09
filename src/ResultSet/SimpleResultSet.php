<?php

namespace DalPraS\OAuth2\Client\ResultSet;

/**
 * Class SimpleResultSet
 */
class SimpleResultSet extends \ArrayObject implements ResultSetInterface
{

    /**
     * Initialize ArrayObject with parsed response from the GoToWebinar 
     * 
     * @param array|string $response
     */
    public function __construct($response) {
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
    public function getData(): \ArrayObject
    {
        return $this;
    }
    
}
