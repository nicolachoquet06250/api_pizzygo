<?php

require_once __DIR__.'/extendable_classes/autoload.php';

if(is_file(__DIR__.'/mvc/models/BaseModel.php')) {
	require_once __DIR__.'/mvc/models/BaseModel.php';
}

if(is_file(__DIR__.'/vendor/autoload.php')) {
	require_once __DIR__.'/vendor/autoload.php';
}

if(is_dir(__DIR__.'/mvc/controllers')) {
	$dir = opendir(__DIR__.'/mvc/controllers');
	while (($elem = readdir($dir)) !== false) {
		if ($elem !== '.' && $elem !== '..') {
			if(is_file(__DIR__.'/mvc/controllers/'.$elem)) {
				require_once __DIR__.'/mvc/controllers/'.$elem;
			}
			elseif (is_dir(__DIR__.'/mvc/controllers/'.$elem)) {
				$_dir = opendir(__DIR__.'/mvc/controllers/'.$elem);
				while (($_elem = readdir($_dir)) !== false) {
					if ($_elem !== '.' && $elem !== '..') {
						if(is_file(__DIR__.'/mvc/controllers/'.$elem.'/'.$_elem)) {
							require_once __DIR__.'/mvc/controllers/'.$elem.'/'.$_elem;
						}
					}
				}
			}
		}
	}
}
