<?php

use LogadApp\Http\Http;
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

    public function testGet()
    {
        $url = 'https://example.com';
        $http = Http::get($url);

        $this->assertInstanceOf(Http::class, $http);
        $this->assertEquals($url, $http->getUrl());
        $this->assertEquals('GET', $http->getMethod());
        $this->assertEquals('', $http->getBody());
        $this->assertEquals([], $http->getHeaders());
    }

    public function testGetWithHeaders()
    {
        $url = 'https://example.com';
        $headers = ['foo: bar'];

        $http = Http::get($url)
            ->setHeaders($headers);

        $this->assertInstanceOf(Http::class, $http);
        $this->assertEquals($url, $http->getUrl());
        $this->assertEquals('GET', $http->getMethod());
        $this->assertEquals($headers, $http->getHeaders());
    }

    public function testPost()
    {
        $url = 'https://example.com';
        $body = json_encode([
            'name' => 'Michael'
        ]);
        $headers = ['Content-Type: application/json'];

        $http = Http::post($url)
            ->setBody($body)
            ->setHeaders($headers);

        $this->assertInstanceOf(Http::class, $http);
        $this->assertEquals($url, $http->getUrl());
        $this->assertEquals('POST', $http->getMethod());
        $this->assertEquals($body, $http->getBody());
        $this->assertEquals($headers, $http->getHeaders());
    }

    public function testSend()
    {
        $url = 'https://webhook.site/802d2f8d-1ffd-4679-9013-5d6ca29fb263';
        $body = json_encode([
            'name' => 'Michael'
        ]);
        $headers = ['Content-Type: application/json'];

        $http = Http::post($url)
            ->setBody($body)
            ->setHeaders($headers)
            ->send();

        $this->assertInstanceOf(Http::class, $http);
        $this->assertIsArray($http->getResponseHeaders());
    }
}
