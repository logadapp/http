<?php

use LogadApp\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public Request $request;

    public function __construct()
    {
        $this->request = new Request($_GET, $_POST, $_FILES, file_get_contents('php://input'));
    }

    public function testGetMethod()
    {
        $this->assertSame('GET', $this->request->getMethod());
    }
}
