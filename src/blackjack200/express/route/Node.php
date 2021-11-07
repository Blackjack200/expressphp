<?php

namespace blackjack200\express\route;

class Node {
	/** @var Node[] */
	public array $children = [];
	public ?Handler $handler = null;
	public string $name;

	public function children(string $name) : Node {
		if (!isset($this->children[$name])) {
			$this->children[$name] = new Node();
			$this->children[$name]->name = $name;
			$this->children[$name]->handler = new ClosureHandler(static fn() => null);
		}
		return $this->children[$name];
	}

	public function hasChildren(string $name) : bool {
		return isset($this->children[$name]);
	}
}