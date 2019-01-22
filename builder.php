<?php

require_once __DIR__.'/autoload.php';

Command::create(
	Command::clean_args($argv)
);
