<?php
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 20 Jan, 2023 12:38 AM
// +------------------------------------------------------------------------+

// +----------------------------+
// | Test
// +----------------------------+

require 'vendor/autoload.php';

use Logadapp\Http\Http;
use Logadapp\Http\Response;
use Logadapp\Http\Request;

$request = (new Request($_GET, $_POST, $_FILES, file_get_contents('php://input'), getallheaders()));

$response = new Response;

// print_r($request->getBody());

$response->json([
    'error' => false,
    'method' => $request->getMethod(),
    'body' => $request->getBody(),
    'headers' => $request->getHeaders(),
    'query' => $request->getQueryParams(),
    'content-type' => $request->getContentType()
])
    ->send();

if (!empty($request->getQueryParams())) {
    $response
        ->json([
            'error' => false,
            'message' => 'It\'s all good',
            'data' => $request->getQueryParams()
        ])
        // Direct status code can be used. But I recommend this
        ->withStatus(Response::HTTP_OK)
        ->send();
} else {
    $response
        ->json([
            'error' => true,
            'message' => 'Please add query parameter(s)',
            'data' => []
        ])
        // Direct status code can be used. But I recommend this
        ->withStatus(Response::HTTP_BAD_REQUEST)
        ->send();

    $postResponse = Http::post('http://localhost')
        ->setBody(json_encode([
            'name' => 'Michael'
        ]))
        ->send();

    echo 'Body', PHP_EOL;
    print_r($postResponse->getResponseBody());
    echo PHP_EOL;

    echo 'Headers', PHP_EOL;
    print_r($postResponse->getResponseHeaders());
    echo PHP_EOL;

    Http::build([
        'url' => 'https://www.logad.net',
    ])
    ->setBody('Hello World')
    ->setHeaders([
        'Content-Type' => 'text/plain'
    ])
        ->send();
}
