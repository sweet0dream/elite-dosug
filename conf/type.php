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
				'title' => sprintf('Сегодня индивидуалки %s обслуживают мужчин ласковыми телами и чувственной нежностью', $city['value'][1]),
				'description' => sprintf('Милые проститутки и индивидуалки %1$s стоят на страже сексуальных фантазий своих клиентов для выполнения их желаний. Проститутки %1$s на любой вкус и кошелёк клиенты которых будут довольны.', $city['value'][1]),
				'keywords' => sprintf('индивидуалки %1$s, проститутки %1$s, шлюхи %1$s, бляди %1$s', mb_strtolower($city['value'][1]))
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
				'title' => sprintf('Проститутки %1$s интим салоны города работают сейчас для командировок и саун лучше индивидуалки %1$s', $city['value'][1]),
				'description' => sprintf('Интим салоны проститутки %1$s сейчас открыты для визитов и вызова индивидуалки %1$s в городе. Низкие цены за досуг вызывают восторг и удовлетворение твоих желаний.', $city['value'][1]),
				'keywords' => sprintf('интим салоны %1$s, интимсалоны %1$s, проститутки %1$s, шлюхи %1$s, бляди %1$s', mb_strtolower($city['value'][1]))
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
				'title' => sprintf('Для девушек и женщин доступен мужской эскорт %s стриптиз для прекрасной половины', $city['value'][1]),
				'description' => sprintf('Женщинам доступны мужчины по вызову %1$s для голодных до мужской ласки девушек и парней. Мужчины проститутки %1$s удовлетворят самые похотливые фантазии люой клиентки и эротические мечты сбудутся.', $city['value'][1]),
				'keywords' => sprintf('мужчины секс %1$s, мужской эскорт %1$s, проститутки %1$s', mb_strtolower($city['value'][1]))
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
				'title' => sprintf('Для любителей экзотики представлены трансы %s с красивой грудью и упругими членами для активного и пассивного интим досуга', $city['value'][1]),
				'description' => sprintf('Транссексуалки %1$s будут нежно ласкать тебя - окунись в экзотическую атмосферу, в этом помогут трансы %1$s и тебя будут удовлетворять девочки с членом', $city['value'][1]),
				'keywords' => sprintf('трансы %1$s, транссексуалки %1$s, проститутки %1$s', mb_strtolower($city['value'][1]))
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