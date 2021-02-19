<?php

$files = scandir(__DIR__);

foreach ($files as $file) {
	if (in_array($file, ['.', '..', 'kernel.php'])) {
		continue;
	}

	include_once(__DIR__ . '/' . $file);
}
