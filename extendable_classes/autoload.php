<?php

$dir = opendir(__DIR__);
while (($elem = readdir($dir)) !== false) {
	if($elem !== '.' && $elem !== '..' && $elem !== 'autoload.php') {
		require_once __DIR__.'/'.$elem;
	}
}