<?php
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 19 Apr, 2023 9:23 AM
// +------------------------------------------------------------------------+
// | Copyright (c) 2022 Logad Networks. All rights reserved.
// +------------------------------------------------------------------------+

// +----------------------------+
// | 
// +----------------------------+

use Logadapp\Http\Request;
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
