<?php

namespace blackjack200\express;

use blackjack200\express\network\SocketServer;
use blackjack200\express\protocol\Request;
use blackjack200\express\protocol\Response;
use blackjack200\express\route\Router;
use blackjack200\express\utils\InetAddress;
use InvalidArgumentException;

class App {
	private bool $running = true;
	private SocketServer $socket;

	public function __construct(private Router $router) {
	}

	public function listen(int $port) : void {
		if ($port < 0 or $port > 65535) {
			throw new InvalidArgumentException("Invalid port range");
		}
		$this->socket = new SocketServer(new InetAddress("0.0.0.0", $port, 4));
		while ($this->running) {
			$client = $this->socket->accept();
			if ($client !== null) {
				if ($client->read($buf, 8192)) {
					$req = Request::parse($buf);
					if ($req !== null) {
						$node = $this->router->locate($req->path());
						//var_export($node);
						/** @var Response $resp */
						$resp = $node->handler->handle($req);
						if ($resp !== null) {
							$client->write($resp->encode());
						}
					}
				}
				$client->close();
			}
		}
	}

	public function shutdown() : void {
		$this->running = false;
		$this->socket->shutdown();
	}
}