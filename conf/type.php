<?php
use Sweet0dream\IntimAnketaContract;
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
			'fields' => (new IntimAnketaContract('ind'))->getField(),
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
			'fields' => (new IntimAnketaContract('sal'))->getField(),
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
			'fields' => (new IntimAnketaContract('man'))->getField(),
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
			'fields' => (new IntimAnketaContract('tsl'))->getField(),
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