<?php

require_once __DIR__.'/Base.php';

$dir = opendir(__DIR__);
while (($elem = readdir($dir)) !== false) {
	if($elem !== '.' && $elem !== '..' && $elem !== 'autoload.php') {
		if(is_file(__DIR__.'/'.$elem)) {
			require_once __DIR__.'/'.$elem;
		}
	}
}