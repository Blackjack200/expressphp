<?php

namespace blackjack200\express\protocol;

use DateTimeInterface;

class Response {
	private int $status = 200;
	private string $content;
	private string $server;
	private string $contentType;

	public function setContent(string $content) : void {
		$this->content = $content;
	}

	public function setContentType(string $contentType) : void {
		$this->contentType = $contentType;
	}

	public function setServer(string $server) : void {
		$this->server = $server;
	}

	public function setStatus(int $status) : void {
		$this->status = $status;
	}

	public static function new(string $content, string $contentType) : self {
		$r = new self();
		$r->content = $content;
		$r->contentType = $contentType;
		$r->server = 'ExpressPHP';
		return $r;
	}

	public static function new404() : self {
		$r = new self();
		$r->status = 404;
		$r->content = "404 Not Found\n";
		$r->contentType = 'text/html';
		$r->server = 'ExpressPHP';
		return $r;
	}

	public function encode() : string {
		return implode("\r\n", [
			"HTTP/1.1 $this->status OK",
			"Date: " . gmdate(DateTimeInterface::RFC7231),
			"Server: $this->server",
			"Last-Modified: " . gmdate(DateTimeInterface::RFC7231),
			"Accept-Ranges: bytes",
			"Content-Length: " . strlen($this->content),
			"Content-Type: $this->contentType",
			"",
			$this->content,
		]);
	}
}