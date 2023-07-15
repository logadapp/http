<?php
/**
 * Invalid argument Exception
 * @package Logadapp\Http
 * @author Michael Arawole <michael@logad.net>
 */

declare(strict_types=1);

namespace LogadApp\Http\Exception;

use Exception;

class InvalidArgumentException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
