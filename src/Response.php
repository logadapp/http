<?php
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 20 Jan, 2023 12:30AM
// +------------------------------------------------------------------------+

declare(strict_types=1);

namespace LogadApp\Http;

final class Response
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;
    const HTTP_NOT_MODIFIED = 304;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    /* MORE */

    private string $body = '';
    private int $status = self::HTTP_OK;
    private array $headers = [];

    public function withHeader(string $name, string $value): Response
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function json(array $data): Response
    {
        $this->setContent(json_encode($data));
        $this->withHeader('Content-Type', 'application/json');
        return $this;
    }

    public function setContent(mixed $content): Response
    {
        $this->body = $content;
        return $this;
    }

    public function withStatus(int $statusCode): Response
    {
        $this->status = $statusCode;
        return $this;
    }

    /**
     * Get Response content
     * @return string
     */
    public function getContent():string
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function send():void
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->body;
    }
}
