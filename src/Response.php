<?php
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 20 Jan, 2023 12:30AM
// +------------------------------------------------------------------------+

namespace LogadApp\Http;

final class Response
{
    private mixed $body;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_OK = 200;
    public const HTTP_BAD_REQUEST = 400;
    /* MORE */

    public function asJson(): Response
    {
        header('Content-Type:application/json');
        echo json_encode($this->body);
        return $this;
    }

    public function setContent(mixed $content): Response
    {
        $this->body = $content;
        return $this;
    }

    public function write(string $content): Response
    {
        echo $content;
        return $this;
    }

    public function withStatus(int $statusCode): Response
    {
        http_response_code($statusCode);
        return $this;
    }
}
