<?php

/**
 * HTTP Request
 * @package Logadapp\Http
 * @author Michael Arawole <michael@logad.net>
 */

declare(strict_types=1);
namespace LogadApp\Http;

use Exception;
use LogadApp\Http\Exception\InvalidArgumentException;
use LogadApp\Http\Exception\InvalidHttpMethodException;
use LogadApp\Http\Exception\RequestFailedException;

final class Http
{
    private bool $ignoreSsl = false;

    private Response $response;

    private string $requestError = '';

    private int $timeout = 30;

    private string $method;

    private string $url;

    private string $body;

    private array $headers;

    /**
     * Http constructor.
     * @param string $url The URL for the HTTP request.
     * @param string $method The HTTP request method.
     * @param string $body The request body for the HTTP request.
     * @param array $headers The headers for the HTTP request.
     */
    public function __construct(string $url = '', string $method = 'GET', string $body = '', array $headers = [])
    {
        $this->setUrl($url);
        $this->setMethod($method);
        $this->setBody($body);
        $this->setHeaders($headers);
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
     * Get the URL for the HTTP request.
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
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
     * Get the timeout for the HTTP request.
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Set the request body for the HTTP request.
     * @param string $value The request body for the HTTP request.
     * @return Http Returns the Http instance.
     */
    public function setBody(string $value): self
    {
        $this->body = $value;
        return $this;
    }

    /**
     * Get the request body for the HTTP request.
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Set a header for the HTTP request.
     * @param string $name The name of the header.
     * @param string $value The value of the header.
     * @return Http Returns the Http instance.
     */
    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = htmlentities($value, ENT_QUOTES, 'UTF-8');
        ;
        return $this;
    }

    /**
     * Set content-type and accept headers as json
     * @return Http Returns the Http instance.
     */
    public function isJson(): self
    {
        $this->withHeader('Accept', 'application/json');
        $this->withHeader('Content-Type', 'application/json');
        return $this;
    }

    /**
     * Set the ignore ssl flag for the HTTP request.
     * @return Http Returns the Http instance.
     */
    public function ignoreSsl():self
    {
        $this->ignoreSsl = true;
        return $this;
    }

    /**
     * Set the bearer token for the HTTP request.
     * @param string $value bearer token
     * @return Http Returns the Http instance.
     */
    public function withToken(string $value): self
    {
        $this->headers['Authorization'] = 'Bearer ' . $value;
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
     * Get the headers for the HTTP request.
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the request method for the HTTP request.
     * @param string $method
     * @return $this
     * @throws InvalidHttpMethodException
     */
    public function setMethod(string $method): self
    {
        if (!in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'HEAD'])) {
            throw new InvalidHttpMethodException();
        }

        $this->method = $method;
        return $this;
    }

    /**
     * Get the request method for the HTTP request.
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
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
     * Create a new Http instance with the PUT method.
     * @param string $url The URL for the HTTP request.
     * @return Http Returns a new instance of Http with the PUT method.
     */
    public static function put(string $url): self
    {
        return new self($url, 'PUT', '', []);
    }

    /**
     * Create a new Http instance with the PATCH method.
     * @param string $url The URL for the HTTP request.
     * @return Http Returns a new instance of Http with the PATCH method.
     */
    public static function patch(string $url): self
    {
        return new self($url, 'PATCH', '', []);
    }

    /**
     * Create a new Http instance with the DELETE method.
     * @param string $url The URL for the HTTP request.
     * @return Http Returns a new instance of Http with the DELETE method.
     */
    public static function delete(string $url): self
    {
        return new self($url, 'DELETE', '', []);
    }

    /**
     * Create a new Http instance with the OPTIONS method.
     * @param string $url The URL for the HTTP request.
     * @return Http Returns a new instance of Http with the OPTIONS method.
     */
    public static function options(string $url): self
    {
        return new self($url, 'OPTIONS', '', []);
    }

    /**
     * Send the HTTP request.
     * @return Response Returns the Http instance.
     * @throws Exception
     */
    public function send(): Response
    {
        if (empty($this->url)) {
            throw new InvalidArgumentException('URL is not set.');
        }

        if (empty($this->method)) {
            throw new InvalidArgumentException('HTTP method is not set.');
        }

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
            CURLINFO_HEADER_OUT => true
        ]);

        if (!empty($this->headers)) {
            $formattedHeaders = [];
            foreach ($this->headers as $name => $value) {
                $formattedHeaders[] = "$name: $value";
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $formattedHeaders);
        }

        if (!empty($this->body)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->body);
        }

        // SSL verification
        if ($this->ignoreSsl) {
            // Disable SSL certificate verification
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        $responseBody = curl_exec($curl);
        $responseHeaders = curl_getinfo($curl, CURLINFO_HEADER_OUT);
        $responseCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $this->requestError = curl_error($curl);
        curl_close($curl);

        if ($responseBody === false || $responseHeaders === false) {
            throw new Exception('Failed to execute cURL request: ' . $this->requestError);
        }

        $this->response = (new Response())
            ->setContent($responseBody)
            ->withHeaders($this->parseHeaders($responseHeaders))
            ->withStatus($responseCode);

        return $this->response;
    }

    /**
     * Parse curl header_out
     * @param string $headerString gotten from `CURLINFO_HEADER_OUT`
     */
    private function parseHeaders(string $headerString): array
    {
        $headers = [];
        $headerLines = explode("\r\n", $headerString);
        foreach ($headerLines as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                $headers[$key] = $value;
            }
        }
        return $headers;
    }

    /**
     * Get the response body from the HTTP request.
     * @return string Returns the response body.
     */
    public function getResponseBody(): string
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

    /**
     * Get the response code from the HTTP request.
     * @return int Returns the response code.
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * Get curl error
     * @return string
     */
    public function getRequestError():string
    {
        return $this->requestError;
    }

    /**
     * Output the request and response details for debugging purposes.
     *
     * This method echoes the request headers, request body, response headers, and response body
     * to the standard output, providing information for debugging and troubleshooting
     * @return void
     */
    public function debug(): void
    {
        echo "Request Headers: " . var_export($this->headers, true) . "\n";
        echo "Request Body: " . var_export($this->body, true) . "\n";
        echo "Response Headers: " . var_export($this->responseHeaders, true) . "\n";
        echo "Response Body: " . var_export($this->responseBody, true) . "\n";
    }
}
