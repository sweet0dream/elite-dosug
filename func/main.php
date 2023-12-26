<?php
    foreach(scandir(dirname(__FILE__)) as $v) {
		if($v != '.' && $v != '..' && $v != 'main.php') {
			require_once(dirname(__FILE__).'/'.$v);
		}
	}

	// post
	function post($data, $file = false) {
		if(is_array($data) && !empty($data)) {
			if(function_exists(key($data).'_post')) {
				if($file) {
					call_user_func(key($data).'_post', $data[key($data)], $file);
				} else {
					call_user_func(key($data).'_post', $data[key($data)]);
				}
			} else {
				die('no exist func: '.key($data).'_post()');
			}
		} else {
			return false;
		}
	}

	// redirect
	function redirect($url) { 
		if(!headers_sent()) {
			header('Location: '.$url);
			exit; 
		} else {
        	return '
				<script type="text/javascript">
					window.location="'.$url.'";
				</script>
				<noscript>
					<meta http-equiv="refresh" content="0;url='.$url.'" />
				</noscript>
			';
        	exit; 
		}
	}

	// not found
	function page404() {
		global $site;
		header('HTTP/1.1 404 Not Found');
		header('Status: 404 Not Found');
		http_response_code(404);
		include $site['path'].'/design/pages/404.php';
	}

	// numeric
	function format_num($number, $suffix) {
		$keys = array(2, 0, 1, 1, 1, 2);
		$mod = $number % 100;
		$suffix_key = ($mod > 7 && $mod < 20) ? 2: $keys[min($mod % 10, 5)];
		return $suffix[$suffix_key];
	}

	// date
	function format_date($date) {
		$termMonth = [
			'01' => 'января',
			'02' => 'февраля',
			'03' => 'марта',
			'04' => 'апреля',
			'05' => 'мая',
			'06' => 'июня',
			'07' => 'июля',
			'08' => 'августа',
			'09' => 'сентября',
			'10' => 'октября',
			'11' => 'ноября',
			'12' => 'декабря'
		];

		$dateDay = explode(' ', $date)[0];
		$timeDay = explode(':', explode(' ', $date)[1])[0].':'.explode(':', explode(' ', $date)[1])[1];

		$day = explode('-', $dateDay)[2];
		$month = explode('-', $dateDay)[1];
		$year = explode('-', $dateDay)[0];

		if (date('d') == $day && date('m') == $month && date('Y') == $year) {
			$result = 'сегодня';
		} elseif (date('d',time()-86400) == $day && date('m') == $month && date('Y') == $year) {
			$result = 'вчера';
		} else {
			$result = ltrim($day, 0).' '.$termMonth[$month];
		}

		if (date('Y') != $year) {
			$result .= ' '.$year.' года';
		}

		$result .= ' в '.$timeDay;

		return $result;
	}

	// phone
	function format_phone($phone) {
        return preg_replace('/^(\d{3})(\d{3})(\d{2})(\d{2})$/iu', '+7($1)$2-$3-$4', $phone);
    }

	// is mobile
	function isMobile() {
		/*$mobile_agent_array = [
			'ipad',
			'iphone',
			'android',
			'pocket',
			'palm',
			'windows ce',
			'windowsce',
			'cellphone',
			'opera mobi',
			'ipod',
			'small',
			'sharp',
			'sonyericsson',
			'symbian',
			'opera mini',
			'nokia',
			'htc_',
			'samsung',
			'motorola',
			'smartphone',
			'blackberry',
			'playstation portable',
			'tablet browser'
		];
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		foreach($mobile_agent_array as $value) {
			if(strpos($agent, $value) !== false) return true;
		}
		return false;*/
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}

	// directory delete
	function dirDel($dir) {
		if(is_dir($dir)) {
			$d = opendir($dir);  
			while(($entry = readdir($d)) !== false) { 
				if($entry != '.' && $entry != '..') { 
					if(is_dir($dir.'/'.$entry)) {  
						dirDel($dir.'/'.$entry);  
					} else {  
						unlink ($dir.'/'.$entry);  
					} 
				} 
			} 
			closedir($d);
			if(rmdir($dir)) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	} 

	// meta data
	function itMeta($route) {
		if(is_array($route)) {
			global $types;
			if(empty($route)) {
				return '
					<title>Проститутки Саратова готовы обслужить клиента - Элит Досуг Саратов</title>
					<meta name="description" content="Круглосуточно проститутки Саратова с нашего сайта обслуживают много клиентов. Индивидуалки Саратова работают в разных концах города для Вашего досуга и отдыха.">
					<meta name="keywords" content="проститутки саратова, индивидуалки саратова, интим салоны саратова, мужчины по вызову саратова, трансы саратова">
				';
			} else {
				if(isset($types[$route[1]])) {
					if(isset($route[2])) {
						if($item = item_decode(db_connect()->where('id', $route[2])->getOne('item'))) {
							return '
								<title>'.$types[$item['type']]['names'][0].' '.$item['info']['name'].($item['type'] != 'sal' ? ', '.$types[$item['type']]['fields']['info']['year']['value'][$item['info']['year']] : '').', ID: '.$item['id'].' - Элит Досуг Саратов</title>
								<meta name="description" content="'.$types[$route[1]]['names'][2].'. '.$item['dopinfo'].'">
								<meta name="keywords" content="'.$types[$route[1]]['meta']['keywords'].'">
							';
						} elseif($route[2] == 'p') {
							return '
								<title>'.$types[$route[1]]['meta']['title'].(isset($route[3]) ? ', '.$route[3].' страница' : '').' - Элит Досуг Саратов</title>
								<meta name="description" content="'.$types[$route[1]]['meta']['description'].(isset($route[3]) ? ' '.$route[3].' страница' : '').'">
								<meta name="keywords" content="'.$types[$route[1]]['meta']['keywords'].'">
							';
						}
					} else {
						return '
							<title>'.$types[$route[1]]['meta']['title'].' - Элит Досуг Саратов</title>
							<meta name="description" content="'.$types[$route[1]]['meta']['description'].'">
							<meta name="keywords" content="'.$types[$route[1]]['meta']['keywords'].'">
						';
					}
				} elseif($route[1] == 'user') {
					return '
						<title>Личный кабинет - Элит Досуг Саратов</title>
						<meta name="description" content="Личный кабинет сайта Элит Досуг для управления рекламой.">
						<meta name="keywords" content="личный кабинет, проститутки саратова">
					';
				} elseif($route[1] == 'item') {
					if(isset($route[2])) {
						if($route[2] == 'add') {
							return '
								<title>Добавление анкеты - Элит Досуг Саратов</title>
								<meta name="description" content="Добавление анкеты.">
								<meta name="keywords" content="добавление анкеты, проститутки саратова">
							';
						} elseif($route[2] == 'edit') {
							return '
								<title>Редактирование анкеты - Элит Досуг Саратов</title>
								<meta name="description" content="Редактирование анкеты.">
								<meta name="keywords" content="личный кабинет, проститутки саратова">
							';
						} elseif($route[2] == 'photo') {
							return '
								<title>Фото анкеты - Элит Досуг Саратов</title>
								<meta name="description" content="Фото анкеты.">
								<meta name="keywords" content="личный кабинет, проститутки саратова">
							';
						}
					}
				} elseif($route[1] == 'placement') {
					return '
						<title>Размещение рекламы - Элит Досуг Саратов</title>
						<meta name="description" content="Информация и стоимость размещения рекламы">
						<meta name="keywords" content="реклама сайт досуг, размещение элит досуг">
					';
				} elseif($route[1] == 'agreement') {
					return '
						<title>Пользовательское соглашение - Элит Досуг Саратов</title>
						<meta name="description" content="Пользовательское соглашение и положение об использовании Сайта Элит Досуг">
						<meta name="keywords" content="соглашение об использовании">
					';
				}
			}
		} else {
			return false;
		}
	}