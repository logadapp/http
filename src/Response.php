<?php 
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 20 Jan, 2023 12:30AM
// +------------------------------------------------------------------------+

namespace LogadApp\Http;

final class Response {
	public $body;

	function asJson(): Response
	{
		header('Content-Type', 'application/json');
		echo json_encode($this->body);
		return $this;
	}

	public function setContent($content): Response {
		$this->body = $content;
		return $this;
	}

	public function write($content): Response {
		echo $content;
		return $this;
	}

	function withStatus(int $statusCode): Response
	{
		http_response_code($statusCode);
		return $this;
	}
}