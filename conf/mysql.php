<?php
$cnf = file_get_contents(__DIR__.'/mysql.txt');
$cnf = explode(";", $cnf);
$_cnf = [];
foreach ($cnf as $item) {
	if($item !== '') {
		$item = str_replace(["\n", "\r"], '', $item);
		$_cnf[explode('=', $item)[0]] = explode('=', $item)[1];
	}
}
return $_cnf;