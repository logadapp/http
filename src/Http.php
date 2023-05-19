<?php

/**
 * HTTP Request
 * @package Logadapp\Http
 * @author Michael Arawole <michael@logad.net>
 */

namespace Logadapp\Http;

final class Http
{
    public array $responseHeaders;

    private string|bool $responseBody;

    private int $timeout;

    private string $method;

    private string $url;

    private mixed $body;

    private array $headers;

    /**
     * Http constructor.
     * @param string $url The URL for the HTTP request.
     * @param string $method The HTTP request method.
     * @param mixed $body The request body for the HTTP request.
     * @param array $headers The headers for the HTTP request.
     */
    public function __construct(string $url, string $method, mixed $body, array $headers)
    {
        $this->setUrl($url);
        $this->method = $method;
        $this->body = $body;
        $this->headers = $headers;
    }

    /**
     * Create an instance of Http using options.
     * @param array $options The options for creating the Http instance.
     * @return Http Returns a new instance of Http.
     */
    public static function build(array $options): self
    {
        return new self(
            $options['url'] ?? '',
            $options['method'] ?? 'GET',
            $options['body'] ?? '',
            $options['headers'] ?? []
        );
    }

    /**
     * Set the URL for the HTTP request.
     * @param string $url The URL for the HTTP request.
     * @return Http Returns the Http instance.
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set the timeout for the HTTP request.
     * @param int $timeout The timeout value for the HTTP request.
     * @return Http Returns the Http instance.
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Set the request body for the HTTP request.
     * @param mixed $body The request body for the HTTP request.
     * @return Http Returns the Http instance.
     */
    public function setBody(mixed $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Set the headers for the HTTP request.
     * @param array $headers The headers for the HTTP request.
     * @return Http Returns the Http instance.
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Create a new Http instance with the GET method.
     * @param string $url The URL for the HTTP request.
     * @return Http Returns a new instance of Http with the GET method.
     */
    public static function get(string $url): self
    {
        return new self($url, 'GET', '', []);
    }

    /**
     * Create a new Http instance with the POST method.
     * @param string $url The URL for the HTTP request.
     * @return Http Returns a new instance of Http with the POST method.
     */
    public static function post(string $url): self
    {
        return new self($url, 'POST', '', []);
    }

    /**
     * Send the HTTP request.
     * @return Http Returns the Http instance.
     */
    public function send(): self
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        if (!empty($this->headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        }

        if ($this->method === 'POST' && !empty($this->body)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->body);
        }

        $this->responseBody = curl_exec($curl);
        $this->responseHeaders = curl_getinfo($curl, CURLINFO_HEADER_OUT);
        curl_close($curl);

        return $this;
    }

    /**
     * Get the response body from the HTTP request.
     * @return string|bool Returns the response body.
     */
    public function getResponseBody(): string|bool
    {
        return $this->responseBody;
    }

    /**
     * Get the response headers from the HTTP request.
     * @return array Returns the response headers.
     */
    public function getResponseHeaders(): array
    {
        return $this->responseHeaders;
    }
}
