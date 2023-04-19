<?php
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 20 Jan, 2023 12:22AM
// +------------------------------------------------------------------------+

namespace Logadapp\Http;

use Rakit\Validation\Validator;

final class Request
{
    private string $uri;
    private string $method;
    private array $headers;
    // private array $serverData;
    private array $get;
    private array $post;
    private array $files;
    private string $rawBody;
    private string $validationError;

    public function __construct(array $get = [], array $post = [], array $files = [], string $rawBody = '', array $headers = [])
    {
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

    public function getQueryParams(string $name = null): array|string
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

    public function validate(array $data, array $rules):bool
    {
        $validator = new Validator;
        $validator->setMessages([
            'required' => ':attribute is required',
            'email' => ':email is not valid'
        ]);
        $validation = $validator->make($data, $rules);
        $validation->validate();
        if ($validation->fails()) {
            $errorMessage = "";
            foreach ($validation->errors()->firstOfAll() as $errMsg) {
                $errorMessage .= "$errMsg, ";
            }
            $errorMessage = rtrim($errorMessage, ', ');
            $this->validationError = $errorMessage;
            return false;
        } else {
            return true;
        }
    }

    public function getValidationError():string
    {
        return $this->validationError;
    }
}
