<?php

require_once __DIR__.'/autoload.php';
// format de données du document
header('Content-Type: application/json');

try {
	if(strstr($_SERVER['SERVER_SOFTWARE'], 'PHP')) {
		echo Router::create($_SERVER['REQUEST_URI'], function (string $controller, HttpService $http) {
			$setup = new Setup($controller);
			return $setup->run();
		});
		return;
	}
	if(!isset($_GET['controller']))
		throw new Exception('Vous devez définir un controlleur !');
	$setup = new Setup($_GET['controller']);
	echo $setup->run();
} catch (Exception $e) {
	exit(
		json_encode(
			[
				'error' => 500,
				'message' => $e->getMessage()
			]
		)
	);
}