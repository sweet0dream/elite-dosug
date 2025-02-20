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

	//get datetime now per city
	function getDateTime($modify = null, $format = 'Y-m-d H:i:s') {
		global $city;
		$dateNow = (new DateTimeImmutable('now', new DateTimeZone($city['timezone'])));
		if (!is_null($modify)) {
			$dateNow = $dateNow->modify($modify);
		}

		return $dateNow->format($format);
	}

	// get city
	function getCity($key) {
		$keyCache = $key . '-config';
		$classCache = new CacheHelper(86400);
		$result = $classCache->getData($keyCache);
		if (!$result) {
			$result = $classCache->setData(
				$keyCache,
				(new ClientHelper())->request('config/get_city/' . $key)->toArray()
			);
		}

		return $result;
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

		if (getDateTime(null, 'd') == $day && getDateTime(null, 'm') == $month && getDateTime(null, 'Y') == $year) {
			$result = 'сегодня';
		} elseif (getDateTime('-1 day', 'd') == $day && getDateTime(null, 'm') == $month && getDateTime(null, 'Y') == $year) {
			$result = 'вчера';
		} else {
			$result = ltrim($day, 0).' '.$termMonth[$month];
		}

		if (getDateTime(null, 'Y') != $year) {
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
		global $city;
		if(is_array($route)) {
			global $types;

			$sitename = 'Элит Досуг ' . $city['value'][0];
			$metaResult = [];
			if(empty($route)) {
				$metaResult = [
					'title' => sprintf('На любой вкус проститутки %1$s в удобное время суток обслужат клиента и индивидуалки %1$s для Вас работают сегодня', $city['value'][1]),
					'description' => sprintf('Индивидуалки %1$s в разных районах города примут с удовольствием клиента - мы поможем выбрать досуг на вечер или ночь. Проститутки %1$s это девушки или трансы и отдых для искушенных мужчин в будни и выходные.', $city['value'][1]),
					'keywords' => sprintf('проститутки %1$s, индивидуалки %1$s, интим салоны %1$s, мужчины по вызову %1$s, трансы %1$s', mb_strtolower($city['value'][1]))
				];
			} else {
				if(isset($types[$route[1]])) {
					if(isset($route[2])) {
						if($item = item_decode((new DatabaseHelper('item'))->fetchOne((int)$route[2])->getResult())) {
							$metaResult = [
								'title' => $types[$item['type']]['names'][0].' '.$item['info']['name'].($item['type'] != 'sal' ? ', '.$types[$item['type']]['fields']['info']['year']['value'][$item['info']['year']] : '').', ID: '.$item['id'],
								'description' => $types[$route[1]]['names'][2].'. '.$item['dopinfo'],
								'keywords' => $types[$route[1]]['meta']['keywords']
							];
						} elseif($route[2] == 'p') {
							$metaResult = [
								'title' => $types[$route[1]]['meta']['title'].(isset($route[3]) ? ', '.$route[3].' страница' : ''),
								'description' => $types[$route[1]]['meta']['description'].(isset($route[3]) ? ' '.$route[3].' страница' : ''),
								'keywords' => $types[$route[1]]['meta']['keywords']
							];
						}
					} else {
						$metaResult = [
							'title' => $types[$route[1]]['meta']['title'],
							'description' => $types[$route[1]]['meta']['description'],
							'keywords' => $types[$route[1]]['meta']['keywords']
						];
					}
				} elseif($route[1] == 'user') {
					$metaResult = [
						'title' => 'Личный кабинет',
						'description' => 'Личный кабинет сайта Элит Досуг для управления рекламой.',
						'keywords' => 'личный кабинет'
					];
				} elseif($route[1] == 'item') {
					if(isset($route[2])) {
						if($route[2] == 'add') {
							$metaResult = [
								'title' => 'Добавление анкеты',
								'description' => 'Добавление анкеты.',
								'keywords' => 'добавление анкеты'
							];
						} elseif($route[2] == 'edit') {
							$metaResult = [
								'title' => 'Редактирование анкеты',
								'description' => 'Редактирование анкеты.',
								'keywords' => 'личный кабинет'
							];
						} elseif($route[2] == 'photo') {
							$metaResult = [
								'title' => 'Фото анкеты',
								'description' => 'Фото анкеты.',
								'keywords' => 'личный кабинет'
							];
						}
					}
				} elseif($route[1] == 'placement') {
					$metaResult = [
						'title' => 'Размещение рекламы',
						'description' => 'Информация и стоимость размещения рекламы.',
						'keywords' => 'реклама сайт досуг, размещение элит досуг'
					];
				} elseif($route[1] == 'agreement') {
					$metaResult = [
						'title' => 'Пользовательское соглашение',
						'description' => 'Пользовательское соглашение и положение об использовании Сайта Элит Досуг.',
						'keywords' => 'соглашение об использовании'
					];
				}
			}

			if (!empty($metaResult)) {
				$result = '';

				foreach ($metaResult as $key => $value) {
					$result .= match ($key) {
						'title' => '<title>' . $value . ' - ' . $sitename . '</title>',
						'description' => '<meta name="description" content="' . $value . '">',
						'keywords' => '<meta name="keywords" content="' . $value . '">'
					};
				}

				return $result;
			}
		} else {
			return false;
		}
	}

	function generate_sitemap($city) {
		header("Content-Type: text/xml;");

		$data = json_decode(file_get_contents('https://rest.elited.ru/config/sitemap/' . $city), true);

		$date = getDateTime();
		
		$sitemap = '
			<urlset xmlns="https://www.livemaps.org/schemas/sitemap/0.9">
				<url>
					<loc>https://'.$_SERVER['HTTP_HOST'].'/</loc>
					<lastmod>'.$date.'</lastmod>
					<changefreq>daily</changefreq>
					<priority>1.0</priority>
				</url>
		';
		foreach($data as $k => $v) {
			$sitemap .= '
				<url>
					<loc>https://'.$_SERVER['HTTP_HOST'].'/'.$k.'/</loc>
					<lastmod>'.$date.'</lastmod>
					<changefreq>weekly</changefreq>
					<priority>0.8</priority>
				</url>
			';
			foreach($v as $id) {
				$sitemap .= '
				<url>
					<loc>https://'.$_SERVER['HTTP_HOST'].'/'.$k.'/'.$id.'/</loc>
					<lastmod>'.$date.'</lastmod>
					<changefreq>weekly</changefreq>
					<priority>0.6</priority>
				</url>
				';
			}
		}
		$sitemap .= '
			</urlset>
		';

		echo $sitemap;
	}

	function generate_robots() {
		header("Content-Type: text/plain;");
		$robots = "User-agent: *\nDisallow: /item/*\nDisallow: /user/*\nHost: ".$_SERVER['HTTP_HOST']."\nSitemap: https://".$_SERVER['HTTP_HOST']."/sitemap.xml";
		echo $robots;
	}