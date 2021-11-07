<?php

namespace blackjack200\express\network;

use blackjack200\express\utils\InetAddress;
use Socket;

class SocketServer {
	private Socket $socket;

	/**
	 * @throws SocketException
	 */
	public function __construct(InetAddress $addr) {
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
		if ($socket === false) {
			throw new SocketException("Failed to create socket fd");
		}
		//socket_set_nonblock($socket);
		if (!socket_bind($socket, $addr->getIP(), $addr->getPort())) {
			socket_close($socket);
			throw new SocketException("Failed to bind socket");
		}
		$this->socket = $socket;
		return socket_listen($socket);
	}

	public function shutdown() : void {
		socket_close($this->socket);
	}

	public function accept() : ?SocketClient {
		$conn = socket_accept($this->socket);
		if ($conn !== false) {
			return new SocketClient($conn);
		}
		return null;
	}
}