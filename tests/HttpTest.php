<?php

use Logadapp\Http\Http;
use PHPUnit\Framework\TestCase;

class HttpTest extends TestCase
{

    public function testBuild()
    {
        $http = Http::build([
            'url' => 'https://www.logad.net',
            'method' => 'GET',
            'body' => '',
            'headers' => []
        ]);

        $this->assertInstanceOf(Http::class, $http);
    }

    public function testSetAndGetUrl()
    {
        $url = 'https://example.com';
        $http = new Http();

        $this->assertInstanceOf(Http::class, $http);

        $http->setUrl($url);
        $this->assertEquals($url, $http->getUrl());
    }

    public function testSetAndGetMethod()
    {
        $method = 'GET';
        $http = new Http();

        $this->assertInstanceOf(Http::class, $http);

        $http->setMethod($method);
        $this->assertEquals($method, $http->getMethod());
    }

    public function testSetAndGetTimeout()
    {
        $timeout = 20;
        $http = new Http();

        $this->assertInstanceOf(Http::class, $http);

        $http->setTimeout($timeout);
        $this->assertEquals($timeout, $http->getTimeout());
    }

    public function testSetAndGetBody()
    {
        $body = json_encode([
            'name' => 'Michael'
        ]);
        $http = new Http();

        $this->assertInstanceOf(Http::class, $http);

        $http->setBody($body);
        $this->assertEquals($body, $http->getBody());
    }

    public function testSetAndGetHeaders()
    {
        $headers = ['Content-Type: application/json'];
        $http = new Http();

        $http->setHeaders($headers);
        $retrievedHeaders = $http->getHeaders();

        $this->assertEquals($headers, $retrievedHeaders);
    }

    public function testSetUrl()
    {
        $
        $http = Http::build([
            'url' => 'https://www.logad.net',
            'method' => 'GET',
            'body' => '',
            'headers' => []
        ]);

        $this->assertIsString($http->getUrl());
    }

    public function testGetResponseHeaders()
    {
        $http = Http::build([
            'url' => 'https://www.logad.net',
            'method' => 'GET',
            'body' => '',
            'headers' => []
        ]);

        $this->assertIsArray($http->getResponseHeaders());
    }

    public function testGetResponseBody()
    {
        $http = Http::build([
            'url' => 'https://www.logad.net',
            'method' => 'GET',
            'body' => '',
            'headers' => []
        ]);

        $this->assertIsString($http->getResponseBody());
    }

    public function testSetTimeout()
    {
        $http = Http::build([
            'url' => 'https://www.logad.net',
            'method' => 'GET',
            'body' => '',
            'headers' => []
        ]);

        $this->assertIsInt($http->getTimeout());
    }

    public function testSetMethod()
    {
        $http = Http::build([
            'url' => 'https://www.logad.net',
            'method' => 'GET',
            'body' => '',
            'headers' => []
        ]);

        $this->assertIsString($http->getMethod());
    }

    public function testSetUrl()
    {
        $http = Http::build([
            'url' => 'https://www.logad.net',
            'method' => 'GET',
            'body' => '',
            'headers' => []
        ]);

        $this->assertIsString($http->url);
    }

    public function testGetBody()
    {
        $http = Http::build([
            'url' => 'https://www.logad.net',
            'method' => 'GET',
            'body' => '',
            'headers' => []
        ]);

        $this->assertIsString($http->body);
    }
}
