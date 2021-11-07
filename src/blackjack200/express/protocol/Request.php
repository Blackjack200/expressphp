<?php

namespace blackjack200\express\protocol;

use AssertionError;

final class Request {
	private string $method;
	private string $path;
	private string $version;
	private array $headers = [];

	private function __construct() {
	}

	public static function readLine(string $line) : ?array {
		$pos = strpos($line, ':');
		if ($pos !== false) {
			$key = substr($line, 0, $pos);
			$val = trim(substr($line, $pos + 1));
			return [$key, $val];
		}
		return null;
	}

	private static function parseFirstLine(?string $first) : ?array {
		$buf = '';
		$arr = [];
		for ($i = 0, $f = strlen($first); $i < $f; $i++) {
			$chr = $first[$i];
			if ($chr !== ' ') {
				$buf .= $chr;
			} else {
				$arr[] = $buf;
				$buf = '';
			}
		}
		if ($buf !== '') {
			$arr[] = $buf;
		}
		if (count($arr) !== 3) {
			return null;
		}
		return $arr;
	}

	public static function parse(string $content) : ?self {
		$lines = explode("\r\n", $content);
		if (count($lines) < 2) {
			return null;
		}
		$firstLine = self::parseFirstLine(array_shift($lines));
		if ($firstLine !== null) {
			$req = new self();
			[$req->method, $req->path, $req->version] = $firstLine;
			foreach ($lines as $line) {
				$l = self::readLine($line);
				if ($l !== null) {
					$req->headers[$l[0]] = $l[1];
				}
			}
			return $req;
		}
		return null;
	}

	public function method() : string {
		return $this->method;
	}

	public function path() : string {
		return $this->path;
	}

	public function version() : string {
		return $this->version;
	}

	public function headers() : array {
		return $this->headers;
	}

	public function header(string $name) : ?string {
		return $this->headers[$name] ?? null;
	}

	public function host() : string {
		return $this->headers["Host"] ?? throw new AssertionError("Host Header should exists at all time");
	}

	public function userAgent() : string {
		return $this->headers["User-Agent"] ?? throw new AssertionError("User-Agent Header should exists at all time");
	}
}