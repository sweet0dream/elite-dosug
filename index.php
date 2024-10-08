<?php

session_start();

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '200M');

require('vendor.php');

date_default_timezone_set($city['timezone'] ?? 'Europe/Moscow');

if(isset($_GET['bug']) && $_GET['bug'] == 1) {
	//var_dump((new CacheHelper())->getData('sar1-config'));
	//print_r(array_map(fn($i) => $i['info'], apcu_cache_info()['cache_list']));
	//print_r(apcu_cache_info());
	apcu_clear_cache();
	//(new CacheHelper())->dropCacheCity($city['domain']);
	//var_dump((new NotifyHelper())->sendSmsForManager('Хуй2'));
}

if (is_null($city)) {
	page404(); die();
}

//get data channel telegram
if (isset($city['social']['telegamChannelId'])) {
	$telegramChannelInfo = getTelegramChannelInfo($city['social']['telegamChannelId']);
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
		if(isset($route[2]) && $route[2] != 'p' && $route[2] != 'f' && !isset((new DatabaseHelper('item'))->fetchOne((int)$route[2])->getResult()['id'])) {
			page404();
		} else {
			include_once $site['path'].'/design/view.php';
			include $site['path'].'/design/head.php';
			include $site['path'].'/design/pages/section.php';
			include $site['path'].'/design/foot.php';
		}
	} elseif($route[1] == 'crontab') {
		(new CronHelper(
			(new ClientHelper())->request('config/get_all_cities')->toArray()
		))->run();
	} elseif($route[1] == 'sitemap.xml') {
		generate_sitemap($city['domain']);
	} elseif($route[1] == 'robots.txt') {
		generate_robots($city['domain']);
	} else {
		page404();
	}
}