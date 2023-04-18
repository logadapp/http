<?php
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 20 Jan, 2023 12:22AM
// +------------------------------------------------------------------------+

namespace Logadapp\Http\Http;

use Rakit\Validation\Validator;

class Request
{
    private array $get;
    private array $post;
    private array $files;
    private array $args;
    private string $rawBody;
    /**
     * @var mixed|null
     */
    private $path;
    private string $validationError;

    public function __construct(array $get = array(), array $post = array(), array $files = array(), string $rawBody = '')
    {
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
        // $this->rawBody = file_get_contents('php://input');
        $this->rawBody = $rawBody;
    }


    public function getPost()
    {
        return $this->post;
    }

    public function getQuery(string $queryName = ''): array|string
    {
        return !empty($queryName) ? ($this->query[$queryName] ?? '') : $this->get;
    }

    public function getArguments():array
    {
        return $this->args;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }
    public function getPath(): string
    {
        return $this->path;
    }

    public function getFiles():array
    {
        return $this->files;
    }

    public function getRawBody():string
    {
        return $this->rawBody;
    }

    public function getParsedBody()
    {
        if (function_exists('cleanBody')) {
            return cleanBody($this->getPOSTBody());
        }
        return $this;
    }
    public function validate($data, array $rules)
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
            $this->setValidationError($errorMessage);
            return false;
        } else {
            return true;
        }
    }

    private function setValidationError(string $errorMessage)
    {
        $this->validationError = $errorMessage;
    }
}
