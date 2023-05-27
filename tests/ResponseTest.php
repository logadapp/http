<?php
use LogadApp\Http\Response;
use PHPUnit\Framework\TestCase;

require '../vendor/autoload.php';

class ResponseTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testJson()
    {
        $expectedJson = json_encode(['error' => false, 'message' => 'Test successful']);
        $response = new Response();
        $response->json([
            'error' => false,
            'message' => 'Test successful'
        ])
        ->send();

        $this->expectOutputString($expectedJson);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetContent()
    {
        $response = new Response();
        $response->setContent('Hello, world')
        ->send();

        $this->expectOutputString('Hello, world');
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithStatus()
    {
        $response = new Response();
        $response->withStatus(404)
            ->send();
        $this->assertEquals(404, http_response_code());
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithHeader()
    {
        $response = new Response();
        $response->withHeader('fooKey', 'barValue')
            ->send();

        $headers = php_sapi_name() === 'cli' ? $this->correctXdebugHeaders(xdebug_get_headers()) : headers_list();

        $this->assertArrayHasKey('fooKey', $headers);
        $this->assertEquals('barValue', $headers['fooKey']);
    }

    private function correctXdebugHeaders(array $headers):array
    {
        $newHeaders = [];
        foreach ($headers as $header) {
            $exp = explode(':', $header);
            $newHeaders[$exp[0]] = trim($exp[1]);
        }
        return $newHeaders;
    }
}
