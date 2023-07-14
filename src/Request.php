<?php

/**
 * Request class
 * @package Logadapp\Http
 * @author Michael Arawole <michael@logad.net>
 */
declare(strict_types=1);
namespace LogadApp\Http;

final class Request
{
    private string $uri;

    private string $method;

    private array $headers;

    private array $get;

    private array $post;

    private array $files;

    private string $rawBody;

    public function __construct(
        array $get = [],
        array $post = [],
        array $files = [],
        string $rawBody = '',
        array $headers = []
    ) {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
        $this->rawBody = $rawBody;
        // $this->serverData = $serverData;
        // $this->headers = $this->setHeadersFromServerData($serverData);
        $this->headers = $headers;
    }

    /*private function setHeadersFromServerData(array $serverData): array
    {
        $headers = [];
        foreach ($serverData as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headers[str_replace('HTTP_', '', $key)] = $value;
            }
        }
        return $headers;
    }*/

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getContentType(): string
    {
        return $this->getHeader('Content-Type');
    }

    public function getQueryParams(): array|string
    {
        return is_null($name) ? $this->get : $this->get[$name] ?? '';
    }

    public function getHeaders():array
    {
        return $this->headers;
    }

    public function getHeader(string $name, string $default = ''): string
    {
        return $this->headers[$name] ?? $default;
    }

    public function getBody(): array
    {
        return $this->getContentType() == 'application/json' ? json_decode($this->rawBody, true) : $this->getPost();
    }

    public function getPost():array
    {
        return $this->post;
    }

    public function getFiles():array
    {
        return $this->files;
    }

    public function getRawBody():string
    {
        return $this->rawBody;
    }

    /*public function getParsedBody()
    {
        if (function_exists('cleanBody')) {
            return cleanBody($this->getBody());
        }
        return $this;
    }*/
}
