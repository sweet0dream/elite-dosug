<?php

global $site, $types;

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

//get data channel telegram
if (isset($channel['telegram']) && $channel['telegram']) {
	$telegramChannelIngo = getTelegramChannelInfo($channel['telegram'])['data']['channelInfo'];
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
	} else {
		page404();
	}
}