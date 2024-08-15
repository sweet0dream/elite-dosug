<?php
    //post
	function user_post($data): void
    {
		global $city;
		if(is_array($data) && isset($data[key($data)])) {
			if(function_exists('user_'.key($data))) {
				(new CacheHelper())->dropCacheCity($city['domain']);
				call_user_func('user_'.key($data), $data[key($data)]);
			} else {
				die('no exist func: user_'.key($data).'()');
			}
		}
	}

    //regin
    function user_regin($data): void
    {
		global $city, $site;
		if(!in_array('', $data)) {

			global $city;
			
			$db = new DatabaseHelper('user');

			$user = [
				'city_id' => $city['id'],
				'type' => 'reg',
				'login' => $data['login'],
				'password' => password_hash($data['password'], PASSWORD_DEFAULT),
				'password_view' => base64_encode($data['password']),
				'phone' => $data['phone'],
				'code' => base64_encode($data['code']),
				'reg_date' => getDateTime(),
				'balance' => $data['balance'] ?? 0
			];

			if(count($db->fetchAll(['login' => $data['login']])->getResult()) > 0) {
				$errors['login'] = 'dublicate';
			}
		}

		if (!isset($errors)) {
			if ($user_id = $db->insertData($user)) {
				$notify = new NotifyHelper();
				$notify->sendSms(
					'Личный кабинет по номеру: '.$data['phone'].' на сайте Элит Досуг '.$city['value'][0].' - '.$site['url'].'. Логин: '.$data['login'].', пароль: '.$data['password'].', секретное слово: '.$data['code'].(isset($data['balance']) ? '. На баланс зачислен бонусные: '.$data['balance'].' рублей.' : ''),
					$data['phone']
				);
				$notify->sendSmsForManager('Новый пользователь на сайте Элит Досуг '.$city['value'][0].'. Логин: '.$data['login'].' / Номер: '.format_phone($data['phone']));

				if (isset($data['balance'])) {
					(new EventHelper($user_id))->add('Бонус зачислен. Ваш баланс: '.$data['balance'].' рублей');
				}
				user_login([
					'login' => $data['login'],
					'password' => $data['password']
				]);
			}
		} else {
			$_SESSION['response'] = [
				'modal' => 'regin',
				'value' => [
					'regin' => $data
				],
				'errors' => is_array($errors) ? ['regin' => $errors] : true
			];
		}
    }

    //login
    function user_login($data): void
    {
		if(!in_array('', $data)) {

			global $city;

			$db = new DatabaseHelper('user');
			
			if($user = $db->fetchAll(['city_id' => $city['id'], 'login' => $data['login']])->getResult()[0]) {
				if(!password_verify($data['password'], $user['password'])) {
					$errors['password'] = 'incorrect';
				}
			} else {
				$errors['login'] = 'noexist';
			}
		}

		if(!isset($errors)) {
			$auth = $db->updateData(
				$user['id'],
				['login_date' => getDateTime()]
			);
			if($auth) {
				setcookie('auth[login]', $data['login'], time()+(60*60*24*30*365));
				$_SESSION['auth'] = [
					'id' => $user['id'],
					'type' => $user['type']
				];
			}
		} else {
			$_SESSION['response'] = [
				'modal' => 'login',
				'value' => [
					'login' => $data
				],
				'errors' => is_array($errors) ? ['login' => $errors] : true
			];
		}
    }

	//change balance
	function user_change_balance($sum, $user_id): bool
    {
		
		$db = new DatabaseHelper('user');

		$user = $db->fetchOne($user_id)->getResult();
		if(isset($user['id']) && $user['balance'] >= $sum && $user['balance']-$sum >= 0) {
			if($db->updateData(
				$user['id'],
				['balance' => $user['balance']-$sum]
			)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	//add balance
	/*function user_add_balance($sum, $user_id): bool
    {

		$db = db_connect();
		
		$user = $db->where('id', $user_id)->getOne('user');
		if($sum > 0) {
			if((new DatabaseHelper('user'))->updateData(
				$user['id'],
				['balance' => $user['balance']+$sum]
			)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}*/

	//all users
	function user_all(
		$city_id = null,
		$type = 'reg',
		$orderBy = ['id' => 'DESC']
	): array|Generator
    {
		global $city;
		return (new DatabaseHelper('user'))->fetchAll([
			'city_id' => $city_id ?? $city['id'],
			'type' => $type
		], $orderBy)->getResult();
	}

	//one user
	function user_one($id): false|array
    {
		global $city;

		return (new DatabaseHelper('user'))->fetchOne($id, ['city_id' => $city['id']])->getResult() ?? false;
	}