<?php
$cnf = file_get_contents(__DIR__.'/mysql.txt');
$_cnf = [];
$cnf = explode("\n", $cnf);
foreach ($cnf as $item) {
	$_cnf[explode('=', $item)[0]] = explode('=', $item)[1];
}
return $_cnf;