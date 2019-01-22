<?php

require_once __DIR__.'/autoload.php';
// format de données du document
header('Content-Type: application/json');
var_dump($_SERVER['REQUEST_URI']);
echo Router::create($_SERVER['REQUEST_URI'], function (string $controller, HttpService $http) {
	$setup = new Setup($controller);
	return $setup->run();
}, function (Exception $e) {
	exit(
		json_encode(
			[
				'error' => 500,
				'message' => $e->getMessage()
			]
		)
	);
});