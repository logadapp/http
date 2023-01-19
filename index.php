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

use Logadapp\Http\Response;
use Logadapp\Http\Request;

$request = (new Request($_GET, $_POST, $_FILES, file_get_contents('php://input')));
$response = new Response;

if (!empty($request->getQuery())) {
    $response
        ->setContent([
            'error' => false,
            'message' => 'It\'s all good',
            'data' => $request->getQuery()
        ])
        ->asJson()
        // Direct status code can be used. But I recommend this
        ->withStatus(Response::HTTP_OK);
} else {
    $response
        ->setContent([
            'error' => true,
            'message' => 'Please add query parameter(s)',
            'data' => []
        ])
        ->asJson()
        // Direct status code can be used. But I recommend this
        ->withStatus(Response::HTTP_BAD_REQUEST);
}