<?php

session_start();

date_default_timezone_set('Europe/Samara');
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '200M');

require('vendor.php');

if(isset($_GET['bug']) && $_GET['bug'] == 1) {
	//debuging
}

if (is_null($city)) {
	page404(); die();
}

//get data channel telegram
if (isset($city['social']['telegamChannelId'])) {
	$telegramChannelInfo = getTelegramChannelInfo($city['social']['telegamChannelId'])['data']['channelInfo'];
}

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
		if(isset($route[2]) && $route[2] != 'p' && $route[2] != 'f' && !isset(db_connect()->where('id', $route[2])->getOne('item')['id'])) {
			page404();
		} else {
			include_once $site['path'].'/design/view.php';
			include $site['path'].'/design/head.php';
			include $site['path'].'/design/pages/section.php';
			include $site['path'].'/design/foot.php';
		}
	} elseif($route[1] == 'crontab') {
		cron_task();
	} elseif($route[1] == 'sitemap.xml') {
		generate_sitemap($city['domain']);
	} elseif($route[1] == 'robots.txt') {
		generate_robots($city['domain']);
	} else {
		page404();
	}
}