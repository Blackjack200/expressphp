<?php

namespace blackjack200\express\network;

use Socket;

class SocketClient {
	public function __construct(private Socket $socket) {
	}

	public function getSocket() : Socket {
		return $this->socket;
	}

	public function close() : void {
		socket_close($this->socket);
	}

	public function read(&$buf, int $len) : bool {
		$data = socket_read($this->socket, $len);
		if ($data === false) {
			return false;
		}
		$buf = $data;
		return true;
	}

	public function write(string $buf) : bool {
		return (socket_write($this->socket, $buf) === strlen($buf));
	}
}