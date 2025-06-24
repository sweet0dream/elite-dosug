<?php

require_once('putenv.php');

ini_set('session.save_path', __DIR__ . '/tmp/');
session_start();
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/tmp/error.log');
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '200M');

require('vendor.php');

global $site, $city, $types;

if(empty($route)) {
	include_once $site['path'].'/design/view.php';
	include $site['path'].'/design/head.php';
	include $site['path'].'/design/pages/index.php';
	include $site['path'].'/design/foot.php';
} else {
	if(file_exists($site['path'].'/design/pages/'.$route[1].'.php')) {
		include_once $site['path'].'/design/view.php';
		include $site['path'].'/design/head.php';
		include $site['path'].'/design/pages/'.$route[1].'.php';
		include $site['path'].'/design/foot.php';
	} elseif(in_array($route[1], array_keys($types))) {
		if(isset($route[2]) && $route[2] != 'p' && $route[2] != 'f' && !isset(new DatabaseHelper('item')->fetchOne((int)$route[2])->getResult()['id'])) {
			page404();
		} else {
			include_once $site['path'].'/design/view.php';
			include $site['path'].'/design/head.php';
			include $site['path'].'/design/pages/section.php';
			include $site['path'].'/design/foot.php';
		}
	} elseif($route[1] == 'crontab') {
		//new CronHelper()->run();
	} elseif($route[1] == 'sitemap.xml') {
		generate_sitemap($city['domain']);
	} elseif($route[1] == 'robots.txt') {
		generate_robots($city['domain']);
	} else {
		page404();
	}
}