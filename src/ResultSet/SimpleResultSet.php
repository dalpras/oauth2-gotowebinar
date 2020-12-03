<?php

namespace DalPraS\OAuth2\Client\ResultSet;

/**
 * Class EmbeddedResponse
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
     * @see \DalPraS\OAuth2\Client\ResultSet\ResultSetInterface::getData()
     */
    public function getData(): \ArrayObject
    {
        return $this;
    }
    
}
