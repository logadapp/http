<?php
/**
 * Request Failed Exception
 * @package Logadapp\Http
 * @author Michael Arawole <michael@logad.net>
 */

declare(strict_types=1);

namespace LogadApp\Http\Exception;

use Exception;

class RequestFailedException extends Exception
{
    public function __construct(string $requestError = "", int $statusCode = 0, Exception $previous = null)
    {
        parent::__construct($requestError, $statusCode, $previous);
    }
}
