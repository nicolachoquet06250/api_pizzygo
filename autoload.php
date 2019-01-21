<?php

require_once __DIR__.'/extendable_classes/autoload.php';

if(is_file(__DIR__.'/mvc/models/BaseModel.php')) {
	require_once __DIR__.'/mvc/models/BaseModel.php';
}

if(is_dir(__DIR__.'/mvc/controllers')) {
	$dir = opendir(__DIR__.'/mvc/controllers');
	while (($elem = readdir($dir)) !== false) {
		if ($elem !== '.' && $elem !== '..') {
			require_once __DIR__.'/mvc/controllers/'.$elem;
		}
	}
}
