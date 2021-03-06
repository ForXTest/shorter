<?php

namespace Shortener;

/**
 * Class Response
 *
 * @package Shortener
 */
class Response
{
    /**
     * HTTP status code
     *
     * @var int
     */
    private $code = 200;

    /**
     * Response body
     *
     * @var string
     */
    private $body = '';

    /**
     * Response headers
     *
     * @var array
     */
    private $headers = [];

    /** @var int  */
    public const STATUS_OK = 200;

    /** @var int  */
    public const STATUS_MOVED_PERMANENTLY = 301;

    /** @var int  */
    public const STATUS_NOT_FOUND = 404;

    /** @var int  */
    public const STATUS_INTERNAL_ERROR = 500;

    /**
     * HTTP response status codes
     *
     * @var array
     */
    private const STATUS_DESCRIPTIONS = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        self::STATUS_OK => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        self::STATUS_MOVED_PERMANENTLY => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        self::STATUS_NOT_FOUND => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        self::STATUS_INTERNAL_ERROR => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    ];

    /**
     * Return a response as a string and send headers
     *
     * @return string
     */
    public function __toString(): string
    {
        $this->sendHeaders();

        return $this->body;
    }

    /**
     * Set a response body
     *
     * @param mixed $body
     * @return $this
     */
    public function setBody($body): self
    {
        if (!is_string($body)) {
            $this->body = json_encode($body);
            $this->setHeader('Content-Type', 'application/json');

            return $this;
        }
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Set a header
     *
     * @param string $name header name
     * @param string $value header value
     * @return Response
     */
    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = [
            'name' => $name,
            'value' => $value
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set HTTP status code of response
     *
     * @param int $code
     * @return Response
     */
    public function setHttpCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->code;
    }

    /**
     * Set redirect
     *
     * @param string $url
     * @return Response
     */
    public function setRedirect(string $url): self
    {
        $this->setHttpCode(self::STATUS_MOVED_PERMANENTLY);
        $this->setHeader('Location', $url, self::STATUS_MOVED_PERMANENTLY);

        return $this;
    }

    /**
     * Send headers
     */
    private function sendHeaders(): void
    {
        header("HTTP/1.1 {$this->code} " . self::STATUS_DESCRIPTIONS[$this->code] ?? 'Unknown', true, $this->code);

        if (empty($this->headers)) {
            return;
        }

        foreach ($this->headers as $header) {
            header("{$header['name']}: {$header['value']}", true, $header['code']);
        }
    }
}
