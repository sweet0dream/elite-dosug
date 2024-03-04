<?php
    //post
	function user_post($data): void
    {
		if(is_array($data) && isset($data[key($data)])) {
			if(function_exists('user_'.key($data))) {
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
			
			$db = db_connect();

			$user = [
				'city_id' => $city['id'],
				'type' => 'reg',
				'login' => $data['login'],
				'password' => password_hash($data['password'], PASSWORD_DEFAULT),
				'password_view' => base64_encode($data['password']),
				'phone' => $data['phone'],
				'code' => base64_encode($data['code']),
				'reg_date' => $db->now()
			];

			if(isset($db->where('login', $data['login'])->getOne('user')['id'])) {
				$errors['login'] = 'dublicate';
			}

		}

		if(!isset($errors)) {
			if($db->insert('user', $user)) {
				(new Notify())->sendSms(
					'Личный кабинет по номеру: '.$data['phone'].' на сайте Элит Досуг '.$city['value'][0].' - '.$site['url'].'. Логин: '.$data['login'].', пароль: '.$data['password'].', секретное слово: '.$data['code'],
					$data['phone']
				);
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

			$db = db_connect();

			$db->where('city_id', $city['id']);
			
			if($user = $db->where('login', $data['login'])->getOne('user')) {
				if(!password_verify($data['password'], $user['password'])) {
					$errors['password'] = 'incorrect';
				}
			} else {
				$errors['login'] = 'noexist';
			}
		}

		if(!isset($errors)) {
			if($db->where('id', $user['id'])->update('user', ['login_date' => $db->now()])) {
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
		
		$db = db_connect();

		$user = $db->where('id', $user_id)->getOne('user');
		if(isset($user['id']) && $user['balance'] >= $sum && $user['balance']-$sum >= 0) {
			if($db->where('id', $user['id'])->update('user', ['balance' => $user['balance']-$sum])) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	//add balance
	function user_add_balance($sum, $user_id): bool
    {

		$db = db_connect();
		
		$user = $db->where('id', $user_id)->getOne('user');
		if($sum > 0) {
			if($db->where('id', $user['id'])->update('user', ['balance' => $user['balance']+$sum])) {
				(new Notify())->sendSms(
					'Элит Досуг: Юзер ID: '.$user['id'].' залил '.$sum.' рублей. Баланс: '.$user['balance']+$sum.' рублей',
					9053242575
				);
				(new Event($user['id']))->add('Вы пополнили: '.$sum.' рублей. Текущий баланс: '.$user['balance']+$sum.' рублей.');
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	//all users
	function user_all($type = 'reg', $orderBy = [['id', 'DESC']]): array|Generator
    {
		global $city;
		$db = db_connect();

		$db->where('city_id', $city['id']);

		if($type) {
			$db->where('type', $type);
		}

		foreach($orderBy as $order) {
			$db->orderBy($order[0], $order[1]);
		}

		return $db->get('user');
	}

	//one user
	function user_one($id): false|array
    {
		if($id) {

			global $city;

			$db = db_connect();

			$db->where('city_id', $city['id']);

			$user = $db->where('id', $id)->getOne('user');
			return isset($user['id']) ? $user : false;
		} else {
			return false;
		}
	}