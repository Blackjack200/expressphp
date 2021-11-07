<?php

require __DIR__ . '/vendor/autoload.php';

use blackjack200\express\App;
use blackjack200\express\protocol\Request;
use blackjack200\express\protocol\Response;
use blackjack200\express\route\ClosureHandler;
use blackjack200\express\route\Router;

$router = new Router();
$app = new App($router);
$router->register('shutdown', new ClosureHandler(static function (Request $req) use ($app) {
	$app->shutdown();
	return Response::new("Shutdown\n", "text/html");
}));
$router->register('test/tt/t2/', new ClosureHandler(static fn(Request $req) => Response::new("Hello world\nYour are in {$req->path()}\n", "text/html")));
$router->register('test/tt/t1/', new ClosureHandler(static fn(Request $req) => Response::new("Hello world1\nYour are in {$req->path()}\n", "text/html")));
$router->register('test/tt/', new ClosureHandler(static fn(Request $req) => Response::new("Hello world3\nYour are in {$req->path()}\n", "text/html")));
$app->listen(1123);