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

    /**
     * Create instance from global variables
     * @since 0.4.4
     * @return self
     */
    public static function createFromGlobals(): self
    {
        return new self(
            $_GET,
            $_POST,
            $_FILES,
            file_get_contents('php://input'),
            getallheaders()
        );
    }

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

    public function getQueryParams(): array
    {
        return $this->get;
    }

    public function getParam(string $name, string $default = ''): string
    {
        return $this->get[$name] ?? $default;
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
        return
            !empty($this->getPost()) ? $this->getPost()
                : json_decode($this->rawBody, true);
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
}
