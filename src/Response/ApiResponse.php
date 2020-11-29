<?php
namespace DalPraS\OAuth2\Client\Response;

use Doctrine\DBAL\Types\ObjectType;

/**
 * Class ApiResponse
 * @package DalPraS\OAuth2\Client\Response
 */
class ApiResponse
{

    public $response;
    protected $type;
    protected $path;
    protected $args;

    public function __construct($response, $type = "", $path = "", $args = "")
    {
        $this->response = $response;
        $this->type = $type;
        $this->path = $path;
        $this->args = $args;
    }

    public function getData(){
        // Non paged response
        if (isset($this->response['response'])) {
            return $this->response['response'];
        }

        if (!isset($this->response['page'])) {
            return $this->response;
        }

        // Paged response
        if (isset($this->response['_embedded'])) {
            return $this->response['_embedded'][$this->type];
        }

        // Empty paged response
        return [];
    }

    public function getPaging(){
        return $this->response['page'];
    }
}
