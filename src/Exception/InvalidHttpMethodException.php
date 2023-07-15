<?php
/**
 * Invalid Http method Exception
 * @package Logadapp\Http
 * @author Michael Arawole <michael@logad.net>
 */

declare(strict_types=1);

namespace LogadApp\Http\Exception;

use Exception;

class InvalidHttpMethodException extends Exception
{
    public function __construct()
    {
        $message = "HTTP method must be between ('GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'HEAD')";
        parent::__construct($message);
    }
}
