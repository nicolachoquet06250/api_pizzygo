<?php
header("Access-Control-Allow-Origin: *");
require_once __DIR__.'/autoload.php';

echo Router::create($_SERVER['REQUEST_URI'],
	function (string $controller) {
		if(isset($_GET['debug'])) {
			ini_set('display_errors', 'on');
		}
		$setup = new Setup($controller);
		$run = $setup->run();
		return $run;
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