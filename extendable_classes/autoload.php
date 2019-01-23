<?php

require_once __DIR__.'/Base.php';

if(is_dir(__DIR__.'/PHPMailer')) {
	require_once __DIR__.'/PHPMailer/src/POP3.php';
	require_once __DIR__.'/PHPMailer/src/SMTP.php';
	require_once __DIR__.'/PHPMailer/src/OAuth.php';
	require_once __DIR__.'/PHPMailer/src/Exception.php';
	require_once __DIR__.'/PHPMailer/src/PHPMailer.php';
}

$dir = opendir(__DIR__);
while (($elem = readdir($dir)) !== false) {
	if($elem !== '.' && $elem !== '..' && $elem !== 'autoload.php') {
		if(is_file(__DIR__.'/'.$elem)) {
			require_once __DIR__.'/'.$elem;
		}
	}
}