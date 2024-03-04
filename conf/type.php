<?php
use Sweet0dream\IntimAnketaContract;

    $rao = isset($city['rao']) ? array_merge([
        'Только выезд'
    ], $city['rao']) : null;

	if (isset($city['value'])) {

	$types = array(
		//Индивидуалка
		'ind' => array(
			'names' => array('Индивидуалка', 'Индивидуалки', 'Индивидуалки ' . $city['value'][1]),
			'meta' => [
				'title' => 'Работают индивидуалки ' . $city['value'][1] . ' для мужчин',
				'description' => 'Индивидуалки ' . $city['value'][1] . ' с удовольствием обслужат по высшему разряду самые сокровенные желания своего клиента. Проститутки ' . $city['value'][1] . ' - индивидуалки только в салонах.',
				'keywords' => 'индивидуалки ' . $city['value'][1] . ', проститутки ' . $city['value'][1] . ', шлюхи ' . $city['value'][1] . ', бляди ' . $city['value'][1]
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
			'names' => array('Интим салон', 'Интим салоны', 'Интим салоны ' . $city['value'][1]),
			'meta' => [
				'title' => 'Интим салоны ' . $city['value'][1] . ' где ждет Вас выбор девушек',
				'description' => 'Разнообразные интим салоны ' . $city['value'][1] . ' представлены на страницах нашего каталога для всех клиентов которые ждут от проститутки ' . $city['value'][1] . ' полной отдачи и выкладки.',
				'keywords' => 'интим салоны ' . $city['value'][1] . ', интимсалоны ' . $city['value'][1] . ', проститутки ' . $city['value'][1] . ', шлюхи ' . $city['value'][1] . ', бляди ' . $city['value'][1]
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
			'names' => array('Мужчина по вызову', 'Мужчины по вызову', 'Мужчины по вызову ' . $city['value'][1]),
			'meta' => [
				'title' => 'Мужчины для секса ' . $city['value'][2],
				'description' => 'Мужчины для секса ' . $city['value'][2] . ' для изголодавшихся по ласке. Мужской эскорт хорошего класса на страницах нашего каталога Вы найдёте мужчину для секса на любой вкус.',
				'keywords' => 'мужчины секс ' . $city['value'][0] . ', мужской эскорт ' . $city['value'][0] . ', проститутки ' . $city['value'][1]
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
			'names' => array('Транс', 'Трансы', 'Трансы ' . $city['value'][1]),
			'meta' => [
				'title' => 'Экзотические трансы ' . $city['value'][1] . ' покажут новые вершины в сексе',
				'description' => 'Трансы ' . $city['value'][1] . ' - довольно экзотическое направление для жаждущих секса. Транссексуалки ' . $city['value'][1] . ' дадут почувствовать Вам новые эмоции и максимум секса в интимной жизни.',
				'keywords' => 'трансы ' . $city['value'][1] . ', транссексуалки ' . $city['value'][1] . ', проститутки ' . $city['value'][1]
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

	}