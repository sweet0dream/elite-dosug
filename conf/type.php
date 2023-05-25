<?php
    $rao = [
        'Только выезд',
        'Центр',
        'Волжский',
        'Фрунзенский',
        'Октябрьский',
        'Заводской',
        'Ленинский'
    ];
	$types = array(
		//Индивидуалка
		'ind' => array(
			'names' => array('Индивидуалка', 'Индивидуалки', 'Индивидуалки Саратова'),
			'meta' => [
				'title' => 'Работают индивидуалки Саратова для мужчин',
				'description' => 'Индивидуалки Саратова с удовольствием обслужат по высшему разряду самые сокровенные желания своего клиента. Проститутки Саратова - индивидуалки только в салонах.',
				'keywords' => 'индивидуалки саратова, проститутки саратова, шлюхи саратова, бляди саратова'
			],
			'fields' => array(
				'info' => array(
					'name' => array('name' => 'Имя/Псевдоним', 'type' => 'text', 'require' => 1),
					'year' => array('name' => 'Возраст', 'type' => 'select', 'require' => 1, 'value' => array_map(function($a){return $a.' '.format_num($a, array('год', 'года', 'лет'));}, range(18,60))),
					'height' => array('name' => 'Рост', 'type' => 'select', 'require' => 1, 'value' => array_map(function($a){return $a.' см';}, range(120,210))),
					'weight' => array('name' => 'Вес', 'type' => 'select', 'require' => 1, 'value' => array_map(function($a){return $a.' кг';}, range(30,200))),
					'chest' => array('name' => 'Грудь', 'type' => 'select', 'require' => 1, 'value' => array('Менее 1-го', '1-ый размер', '2-ой размер', '3-ий размер', '4-ый размер', '5-ый размер', '6-ой размер', 'Более 6-го')),
					'hair' => array('name' => 'Волосы', 'type' => 'select', 'require' => 1, 'value' => array('Блондинка', 'Брюнетка', 'Шатенка', 'Рыжая', 'Лысая'))
				),
				'service' => array(
					'sex' => array(
						'name' => 'Секс',
						'value' => array(
							'sk' => 'классический',
							'sa' => 'анальный',
							'sg' => 'групповой'
						)
					),
					'min' => array(
						'name' => 'Минет',
						'value' => array(
							'mp' => 'в презервативе',
							'mb' => 'без презерватива',
							'mg' => 'глубокий'
						)
					),
					'oko' => array(
						'name' => 'Окончание',
						'value' => array(
							'og' => 'на грудь',
							'ol' => 'на лицо',
							'or' => 'в рот'
						)
					),
					'mas' => array(
						'name' => 'Массаж',
						'value' => array(
							'mk' => 'классический',
							'me' => 'эротический',
							'mu' => 'урологический'
						)
					),
					'sad' => array(
						'name' => 'БДСМ',
						'value' => array(
							'sp' => 'порка',
							'ss' => 'страпон',
							'sr' => 'госпожа/рабыня'
						)
					),
					'zre' => array(
						'name' => 'Зрелища',
						'value' => array(
							'zl' => 'лесби шоу',
							'zs' => 'стриптиз',
							'zi' => 'игрушки'
						)
					),
					'ora' => array(
						'name' => 'Оральные',
						'value' => array(
							'ok' => 'кунилингус',
							'oa' => 'анилингус',
							'op' => 'поза 69'
						)
					),
					'izv' => array(
						'name' => 'Извращения',
						'value' => array(
							'iz' => 'золотой дождь',
							'ik' => 'копро',
							'if' => 'фистинг'
						)
					),
					'pro' => array(
						'name' => 'Прочее',
						'value' => array(
							'pe' => 'эскорт',
							'ps' => 'с семейной парой',
							'pv' => 'фото/видео съемка'
						)
					)
				),
				'price' => array(
					'express' => array('name' => 'Экспресс', 'type' => 'text'),
					'onehour' => array('name' => 'Один час', 'type' => 'text', 'require' => 1),
					'twohour' => array('name' => 'Два часа', 'type' => 'text'),
					'night' => array('name' => 'Ночь', 'type' => 'text')
				),
				'dop' => array(
					'text' => array('name' => 'Дополнительно о себе', 'type' => 'textarea', 'require' => 1)
				)
			),
			'validate' => function ($data) {
				if(is_array($data)) {
					$errors = [];
					if(!isset($data['phone']) || $data['phone'] == '') {
						$errors['info']['phone'] = 'empty';
					}
					foreach($data['info'] as $k => $v) {
						if(!isset($v) || $v == '' || $v == 'null') {
							$errors['info'][$k] = 'empty';
						}
					}
					if(!isset($data['service'])) {
						$errors['service']['service'] = 'unchecked';
					}
					if(!isset($data['price']['onehour']) || $data['price']['onehour'] == '') {
						$errors['price']['onehour'] = 'empty';
					}
					if(!isset($data['dopinfo']) || $data['dopinfo'] == '') {
						$errors['dopinfo'] = 'empty';
					}
					if(!empty($errors)) {
						$_SESSION['response'] = [
							'errors' => $errors,
							'value' => $data
						];
						return false;
					} else {
						return $data;
					}
				} else {
					return false;
				}
			}
		),
		'sal' => array(
			'names' => array('Интим салон', 'Интим салоны', 'Интим салоны Саратова'),
			'meta' => [
				'title' => 'Интим салоны Саратова где ждет Вас выбор девушек',
				'description' => 'Разнообразные интим салоны Саратова представлены на страницах нашего каталога для всех клиентов которые ждут от проститутки Саратова полной отдачи и выкладки.',
				'keywords' => 'интим салоны саратова, интимсалоны саратова, проститутки саратова, шлюхи саратова, бляди саратова'
			],
			'fields' => array(
				'info' => array(
					'name' => array('name' => 'Название салона', 'type' => 'text', 'require' => 1),
				),
				'service' => array(
					'sex' => array(
						'name' => 'Секс',
						'value' => array(
							'sk' => 'классический',
							'sa' => 'анальный',
							'sg' => 'групповой'
						)
					),
					'min' => array(
						'name' => 'Минет',
						'value' => array(
							'mp' => 'в презервативе',
							'mb' => 'без презерватива',
							'mg' => 'глубокий'
						)
					),
					'oko' => array(
						'name' => 'Окончание',
						'value' => array(
							'og' => 'на грудь',
							'ol' => 'на лицо',
							'or' => 'в рот'
						)
					),
					'mas' => array(
						'name' => 'Массаж',
						'value' => array(
							'mk' => 'классический',
							'me' => 'эротический',
							'mu' => 'урологический'
						)
					),
					'sad' => array(
						'name' => 'БДСМ',
						'value' => array(
							'sp' => 'порка',
							'ss' => 'страпон',
							'sr' => 'госпожа/рабыня'
						)
					),
					'zre' => array(
						'name' => 'Зрелища',
						'value' => array(
							'zl' => 'лесби шоу',
							'zs' => 'стриптиз',
							'zi' => 'игрушки'
						)
					),
					'ora' => array(
						'name' => 'Оральные',
						'value' => array(
							'ok' => 'кунилингус',
							'oa' => 'анилингус',
							'op' => 'поза 69'
						)
					),
					'izv' => array(
						'name' => 'Извращения',
						'value' => array(
							'iz' => 'золотой дождь',
							'ik' => 'копро',
							'if' => 'фистинг'
						)
					),
					'pro' => array(
						'name' => 'Прочее',
						'value' => array(
							'pe' => 'эскорт',
							'ps' => 'с семейной парой',
							'pv' => 'фото/видео съемка'
						)
					)
				),
				'price' => array(
					'onehour' => array('name' => 'Один час', 'type' => 'text', 'require' => 1),
					'night' => array('name' => 'Ночь', 'type' => 'text')
				),
				'dop' => array(
					'text' => array('name' => 'Дополнительно о салоне', 'type' => 'textarea', 'require' => 1)
				)
			),
			'validate' => function ($data) {
				if(is_array($data)) {
					$errors = [];
					if(!isset($data['phone']) || $data['phone'] == '') {
						$errors['info']['phone'] = 'empty';
					}
					foreach($data['info'] as $k => $v) {
						if(!isset($v) || $v == '' || $v == 'null') {
							$errors['info'][$k] = 'empty';
						}
					}
					if(!isset($data['service'])) {
						$errors['service']['service'] = 'unchecked';
					}
					if(!isset($data['price']['onehour']) || $data['price']['onehour'] == '') {
						$errors['price']['onehour'] = 'empty';
					}
					if(!isset($data['dopinfo']) || $data['dopinfo'] == '') {
						$errors['dopinfo'] = 'empty';
					}
					if(!empty($errors)) {
						$_SESSION['response'] = [
							'errors' => $errors,
							'value' => $data
						];
						return false;
					} else {
						return $data;
					}
				} else {
					return false;
				}
			}
		),
		'man' => array(
			'names' => array('Мужчина по вызову', 'Мужчины по вызову', 'Мужчины по вызову Саратова'),
			'meta' => [
				'title' => 'Мужчины для секса в Саратове',
				'description' => 'Мужчины для секса в Саратове для изголодавшихся по ласке. Мужской эскорт хорошего класса на страницах нашего каталога Вы найдёте мужчину для секса на любой вкус.',
				'keywords' => 'мужчины секс саратов, мужской эскорт саратов, проститутки саратова'
			],
			'fields' => array(
				'info' => array(
					'name' => array('name' => 'Имя/Псевдоним', 'type' => 'text', 'require' => 1),
					'year' => array('name' => 'Возраст', 'type' => 'select', 'require' => 1, 'value' => array_map(function($a){return $a.' '.format_num($a, array('год', 'года', 'лет'));}, range(18,60))),
					'height' => array('name' => 'Рост', 'type' => 'select', 'require' => 1, 'value' => array_map(function($a){return $a.' см';}, range(120,210))),
					'weight' => array('name' => 'Вес', 'type' => 'select', 'require' => 1, 'value' => array_map(function($a){return $a.' кг';}, range(30,200))),
					'dick' => array('name' => 'Член', 'type' => 'select', 'require' => 1, 'value' => array_map(function($a){return $a.' см';}, range(10,30))),
					'body' => array('name' => 'Тело', 'type' => 'select', 'require' => 1, 'value' => array('Худощавое', 'Обычное', 'Спортивное', 'Плотное')),
					'serv' => array('name' => 'Услуги', 'type' => 'select', 'require' => 1, 'value' => array('Для женщин', 'Для мужчин', 'Для всех')),
					'role' => array('name' => 'Роль', 'type' => 'select', 'require' => 1, 'value' => array('Активный', 'Пассивный', 'Универсал'))
				),
				'service' => array(
					'sex' => array(
						'name' => 'Секс',
						'value' => array(
							'sk' => 'классический',
							'sa' => 'анальный',
							'sg' => 'групповой'
						)
					),
					'min' => array(
						'name' => 'Минет',
						'value' => array(
							'mp' => 'в презервативе',
							'mb' => 'без презерватива',
							'mg' => 'глубокий'
						)
					),
					'oko' => array(
						'name' => 'Окончание',
						'value' => array(
							'og' => 'на грудь',
							'ol' => 'на лицо',
							'or' => 'в рот'
						)
					),
					'mas' => array(
						'name' => 'Массаж',
						'value' => array(
							'mk' => 'классический',
							'me' => 'эротический',
							'mu' => 'урологический'
						)
					),
					'sad' => array(
						'name' => 'БДСМ',
						'value' => array(
							'sp' => 'порка',
							'ss' => 'страпон',
							'sr' => 'госпожа/рабыня'
						)
					),
					'zre' => array(
						'name' => 'Зрелища',
						'value' => array(
							'zl' => 'лесби шоу',
							'zs' => 'стриптиз',
							'zi' => 'игрушки'
						)
					),
					'ora' => array(
						'name' => 'Оральные',
						'value' => array(
							'ok' => 'кунилингус',
							'oa' => 'анилингус',
							'op' => 'поза 69'
						)
					),
					'izv' => array(
						'name' => 'Извращения',
						'value' => array(
							'iz' => 'золотой дождь',
							'ik' => 'копро',
							'if' => 'фистинг'
						)
					),
					'pro' => array(
						'name' => 'Прочее',
						'value' => array(
							'pe' => 'эскорт',
							'ps' => 'с семейной парой',
							'pv' => 'фото/видео съемка'
						)
					)
				),
				'price' => array(
					'express' => array('name' => 'Экспресс', 'type' => 'text'),
					'onehour' => array('name' => 'Один час', 'type' => 'text', 'require' => 1),
					'twohour' => array('name' => 'Два часа', 'type' => 'text'),
					'night' => array('name' => 'Ночь', 'type' => 'text')
				),
				'dop' => array(
					'text' => array('name' => 'Дополнительно о себе', 'type' => 'textarea', 'require' => 1)
				)
			),
			'validate' => function ($data) {
				if(is_array($data)) {
					$errors = [];
					if(!isset($data['phone']) || $data['phone'] == '') {
						$errors['info']['phone'] = 'empty';
					}
					foreach($data['info'] as $k => $v) {
						if(!isset($v) || $v == '' || $v == 'null') {
							$errors['info'][$k] = 'empty';
						}
					}
					if(!isset($data['service'])) {
						$errors['service']['service'] = 'unchecked';
					}
					if(!isset($data['price']['onehour']) || $data['price']['onehour'] == '') {
						$errors['price']['onehour'] = 'empty';
					}
					if(!isset($data['dopinfo']) || $data['dopinfo'] == '') {
						$errors['dopinfo'] = 'empty';
					}
					if(!empty($errors)) {
						$_SESSION['response'] = [
							'errors' => $errors,
							'value' => $data
						];
						return false;
					} else {
						return $data;
					}
				} else {
					return false;
				}
			}
		),
		'tsl' => array(
			'names' => array('Транс', 'Трансы', 'Трансы Саратова'),
			'meta' => [
				'title' => 'Экзотические трансы Саратова покажут новые вершины в сексе',
				'description' => 'Трансы Саратова - довольно экзотическое направление для жаждущих секса. Транссексуалки Саратова дадут почувствовать Вам новые эмоции и максимум секса в интимной жизни.',
				'keywords' => 'трансы саратова, транссексуалки саратова, проститутки саратова'
			],
			'fields' => array(
				'info' => array(
					'name' => array('name' => 'Имя/Псевдоним', 'type' => 'text', 'require' => 1),
					'year' => array('name' => 'Возраст', 'type' => 'select', 'require' => 1, 'value' => array_map(function($a){return $a.' '.format_num($a, array('год', 'года', 'лет'));}, range(18,60))),
					'height' => array('name' => 'Рост', 'type' => 'select', 'require' => 1, 'value' => array_map(function($a){return $a.' см';}, range(120,210))),
					'weight' => array('name' => 'Вес', 'type' => 'select', 'require' => 1, 'value' => array_map(function($a){return $a.' кг';}, range(30,200))),
					'chest' => array('name' => 'Грудь', 'type' => 'select', 'require' => 1, 'value' => array('Менее 1-го', '1-ый размер', '2-ой размер', '3-ий размер', '4-ый размер', '5-ый размер', '6-ой размер', 'Более 6-го')),
					'dick' => array('name' => 'Член', 'type' => 'select', 'require' => 1, 'value' => array_map(function($a){return $a.' см';}, range(10,30))),
					'role' => array('name' => 'Роль', 'type' => 'select', 'require' => 1, 'value' => array('Активный', 'Пассивный', 'Универсал')),
					'hair' => array('name' => 'Волосы', 'type' => 'select', 'require' => 1, 'value' => array('Блондинка', 'Брюнетка', 'Шатенка', 'Рыжая', 'Лысая'))
				),
				'service' => array(
					'sex' => array(
						'name' => 'Секс',
						'value' => array(
							'sk' => 'классический',
							'sa' => 'анальный',
							'sg' => 'групповой'
						)
					),
					'min' => array(
						'name' => 'Минет',
						'value' => array(
							'mp' => 'в презервативе',
							'mb' => 'без презерватива',
							'mg' => 'глубокий'
						)
					),
					'oko' => array(
						'name' => 'Окончание',
						'value' => array(
							'og' => 'на грудь',
							'ol' => 'на лицо',
							'or' => 'в рот'
						)
					),
					'mas' => array(
						'name' => 'Массаж',
						'value' => array(
							'mk' => 'классический',
							'me' => 'эротический',
							'mu' => 'урологический'
						)
					),
					'sad' => array(
						'name' => 'БДСМ',
						'value' => array(
							'sp' => 'порка',
							'ss' => 'страпон',
							'sr' => 'госпожа/рабыня'
						)
					),
					'zre' => array(
						'name' => 'Зрелища',
						'value' => array(
							'zl' => 'лесби шоу',
							'zs' => 'стриптиз',
							'zi' => 'игрушки'
						)
					),
					'ora' => array(
						'name' => 'Оральные',
						'value' => array(
							'ok' => 'кунилингус',
							'oa' => 'анилингус',
							'op' => 'поза 69'
						)
					),
					'izv' => array(
						'name' => 'Извращения',
						'value' => array(
							'iz' => 'золотой дождь',
							'ik' => 'копро',
							'if' => 'фистинг'
						)
					),
					'pro' => array(
						'name' => 'Прочее',
						'value' => array(
							'pe' => 'эскорт',
							'ps' => 'с семейной парой',
							'pv' => 'фото/видео съемка'
						)
					)
				),
				'price' => array(
					'express' => array('name' => 'Экспресс', 'type' => 'text'),
					'onehour' => array('name' => 'Один час', 'type' => 'text', 'require' => 1),
					'twohour' => array('name' => 'Два часа', 'type' => 'text'),
					'night' => array('name' => 'Ночь', 'type' => 'text')
				),
				'dop' => array(
					'text' => array('name' => 'Дополнительно о себе', 'type' => 'textarea', 'require' => 1)
				)
			),
			'validate' => function ($data) {
				if(is_array($data)) {
					$errors = [];
					if(!isset($data['phone']) || $data['phone'] == '') {
						$errors['info']['phone'] = 'empty';
					}
					foreach($data['info'] as $k => $v) {
						if(!isset($v) || $v == '' || $v == 'null') {
							$errors['info'][$k] = 'empty';
						}
					}
					if(!isset($data['service'])) {
						$errors['service']['service'] = 'unchecked';
					}
					if(!isset($data['price']['onehour']) || $data['price']['onehour'] == '') {
						$errors['price']['onehour'] = 'empty';
					}
					if(!isset($data['dopinfo']) || $data['dopinfo'] == '') {
						$errors['dopinfo'] = 'empty';
					}
					if(!empty($errors)) {
						$_SESSION['response'] = [
							'errors' => $errors,
							'value' => $data
						];
						return false;
					} else {
						return $data;
					}
				} else {
					return false;
				}
			}
		)
	);