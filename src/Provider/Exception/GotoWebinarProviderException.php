<?php

namespace DalPraS\OAuth2\Client\Provider\Exception;

use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class GotoWebinarProviderException extends IdentityProviderException {

    /**
     * @var int
     */
    private $httpStatusCode;

    /**
     * @param string $message
     * @param int $code
     * @param array|string $body The response body
     */
    public function __construct($message, $code, $body) {
        $this->httpStatusCode = $code;
        parent::__construct($message, $code, $body);
    }

    /**
     * Creates client exception from response.
     *
     * @param  ResponseInterface $response
     * @param  string $data Parsed response data
     *
     * @return GotoWebinarProviderException
     */
    public static function clientException(ResponseInterface $response, $data)
    {
        return static::fromResponse($response, isset($data['errorCode']) ?  $data['errorCode'] : $response->getReasonPhrase());
    }

    /**
     * Creates oauth exception from response.
     *
     * @param  ResponseInterface $response
     * @param  string $data Parsed response data
     *
     * @return GotoWebinarProviderException
     */
    public static function oauthException(ResponseInterface $response, $data)
    {
        return static::fromResponse($response, isset($data['errorCode']) ?  $data['errorCode'] : $response->getReasonPhrase());
    }

    /**
     * Creates identity exception from response.
     *
     * @param  ResponseInterface $response
     * @param  string $message
     *
     * @return GotoWebinarProviderException
     */
    protected static function fromResponse(ResponseInterface $response, $message = null)
    {
        return new static($message, $response->getStatusCode(), (string) $response->getBody());
    }

    /**
     * Generate a HTTP response.
     *
     * @param ResponseInterface $response
     * @param bool              $useFragment True if errors should be in the URI fragment instead of query string
     * @param int               $jsonOptions options passed to json_encode
     *
     * @return ResponseInterface
     */
    public function generateHttpResponse(ResponseInterface $response) {
        $headers = $this->getHttpHeaders();
        foreach ($headers as $header => $content) {
            $response = $response->withHeader($header, $content);
        }
        $response->getBody()->write($this->response);
        return $response->withStatus($this->getHttpStatusCode());
    }

    /**
     * All error response headers.
     *
     * @return array Array with header values
     */
    public function getHttpHeaders() {
        return [
            'Content-type' => 'application/json',
        ];
    }

    /**
     * Check if the exception has an associated redirect URI.
     *
     * @return bool
     */
    public function hasRedirect()
    {
        return false;
    }

    /**
     * Returns the HTTP status code to send when the exceptions is output.
     *
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

}
