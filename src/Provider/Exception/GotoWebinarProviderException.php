<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\Provider\Exception;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;

class GotoWebinarProviderException extends IdentityProviderException 
{
    public function __construct(
        string $message, 
        protected int $httpStatusCode, 
        mixed $body
    ) {
        parent::__construct($message, $httpStatusCode, $body);
    }

    /**
     * Creates client exception from response.
     */
    public static function clientException(ResponseInterface $response, array $data): self
    {
        return static::fromResponse($response, isset($data['errorCode']) ? $data['errorCode'] : $response->getReasonPhrase());
    }

    /**
     * Creates oauth exception from response.
     */
    public static function oauthException(ResponseInterface $response, array $data): self
    {
        return static::fromResponse($response, isset($data['errorCode']) ?  $data['errorCode'] : $response->getReasonPhrase());
    }

    /**
     * Creates identity exception from response.
     */
    protected static function fromResponse(ResponseInterface $response, ?string $message = null): self
    {
        return new static($message, $response->getStatusCode(), (string) $response->getBody());
    }

    /**
     * Generate a HTTP response.
     */
    public function generateHttpResponse(ResponseInterface $response): ResponseInterface
    {
        $headers = $this->getHttpHeaders();
        foreach ($headers as $header => $content) {
            $response = $response->withHeader($header, $content);
        }
        $response->getBody()->write($this->response);
        return $response->withStatus($this->getHttpStatusCode());
    }

    /**
     * All error response headers.
     */
    public function getHttpHeaders(): array
    {
        return [
            'Content-type' => 'application/json',
        ];
    }

    /**
     * Check if the exception has an associated redirect URI.
     */
    public function hasRedirect(): bool
    {
        return false;
    }

    /**
     * Returns the HTTP status code to send when the exceptions is output.
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
