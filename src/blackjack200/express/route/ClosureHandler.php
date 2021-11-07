<?php

namespace blackjack200\express\route;

use blackjack200\express\protocol\Request;
use blackjack200\express\protocol\Response;
use Closure;

class ClosureHandler extends Handler {
	private Closure $handler;

	public function __construct(Closure $handler) {
		$this->handler = $handler;
	}

	public function handle(Request $request) : ?Response {
		return ($this->handler)($request);
	}
}