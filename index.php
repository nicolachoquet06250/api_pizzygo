<?php

require_once __DIR__.'/autoload.php';
// format de données du document
header('Content-Type: application/json');
echo Router::create($_SERVER['REQUEST_URI'],
	function (string $controller) {
	$setup = new Setup($controller);
	return $setup->run();
},
	function (Exception $e, JsonService $json_service) {
		exit(
			$json_service->encode(
				[
					'error' => 500,
					'message' => $e->getMessage()
				]
			)
		);
	}
);