<?php

namespace blackjack200\express\route;

use blackjack200\express\protocol\Response;

class Router {
	private Node $master;

	public function __construct() {
		$this->master = new Node();
		$this->master->name = '';
		$this->master->handler = new ClosureHandler(static fn() => Response::new404());
	}

	private static function strip(string $str) : string {
		return rtrim($str, '/');
	}

	public function register(string $path, Handler $handler) : void {
		$path = self::strip($path);
		$split = explode('/', $path);
		$curt = $this->master;
		foreach ($split as $p) {
			if ($p === '') {
				continue;
			}
			$curt = $curt->children($p);
		}
		$curt->handler = $handler;
	}

	public function locate(string $path) : Node {
		$path = self::strip($path);
		$split = explode('/', $path);
		$curt = $this->master;
		foreach ($split as $p) {
			if ($p === '') {
				continue;
			}
			if ($curt->hasChildren($p)) {
				$curt = $curt->children($p);
			} else {
				return $this->master;
			}
		}
		return $curt;
	}
}