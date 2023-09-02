<?php
    foreach(scandir(dirname(__FILE__)) as $v) {
		if($v != '.' && $v != '..' && $v != 'main.php') {
			require_once(dirname(__FILE__).'/'.$v);
		}
	}

    $site = [
        'path' => $_SERVER['DOCUMENT_ROOT'],
        'url' => 'https://'.$_SERVER['HTTP_HOST']
    ];