<?php
    //post
	function item_post($data, $files = false): void
    {
		if(is_array($data) && isset($data[key($data)])) {
			if(function_exists('item_'.key($data))) {
				if($files) {
					call_user_func('item_'.key($data), $data[key($data)], $files);
				} else {
					call_user_func('item_'.key($data), $data[key($data)]);
				}
			} else {
				die('no exist func: item_'.key($data).'()');
			}
		}
	}

	function item_encode($data): false|array
    {
		if(is_array($data)) {
			foreach($data as $k => $v) {
				if(is_array($v)) {
					$data[$k] = json_encode($v, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
				}
			}
			return $data;
		} else {
			return false;
		}
	}

	function item_decode($data): false|array
    {
		if(is_array($data)) {
			foreach($data as $k => $v) {
				if(is_object(json_decode($v))) {
					$data[$k] = json_decode($v, true);
				}
			}
			return $data;
		} else {
			return false;
		}
	}

	function item_add($data): void
    {
		global $types;
		if($types[$data['type']]['validate']($data)) {
			
			if($id = db_connect()->insert('item', item_encode($data))) {
				(new Event(item_one($id)['user_id']))->add('Анкета ID '.$id.' была добавлена.');
				global $site;
				redirect($site['url'].'/item/photo/'.$id.'/');
			}
		}
	}

	function item_edit($data): void
    {
		global $types;
		if($types[$data['type']]['validate']($data)) {
			
			$data = item_encode($data);
			$data['date_edit'] = db_connect()->now();
			$id = $data['id']; unset($data['id']);
			db_connect()->where('id', $id)->update('item', $data);
			(new Event($data['user_id']))->add('Анкета ID '.$id.' была отредактирована.');
		}
	}

	function item_delete($data) {
		if(is_array($data)) {
			global $site;
			$id = $data['id'];
			$user_id = $data['user_id'];
			if($item = item_one($id, $user_id)) {
				if(dirDel($site['path'].'/media/photo/'.$id.'/')) {
					review_del_all($id);
					
					db_connect()->where('id', $id)->where('user_id', $user_id)->delete('item');
					(new Event($user_id))->add('Анкета ID '.$id.' была удалена.');
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function item_photo($data, $files = false) {
		if(is_array($data)) {
			global $site;
			if(key($data) == 'add' && !empty($files) && isset($files[0])) {
				$id = $data[key($data)]['id'];
				if($item = item_one($id)) {
					if($item['id'] == $id) {
						$path = $site['path'].'/media/photo/'.$id.'/';
						if(!is_dir($path)) mkdir($path);
						if($item['photo'] != '') $photo = explode(',', $item['photo']);
						foreach($files as $file) {
							$filename = $id.date('YmdHis').rand(1111,9999);
							$img = new \claviska\SimpleImage();
							if($img->fromFile($file)->bestFit(1000,1000)->toFile($path.$filename.'.jpg', 'image/jpeg', 100)) {
								$photo[] = $filename;
							}
						}
						if(!empty($photo)) {
							
							db_connect()->where('id', $item['id'])->update('item', ['photo' => implode(',', $photo), 'status_real' => 0]);
						}
					}
				}
			} elseif(key($data) == 'del') {
				$id = $data[key($data)]['id'];
				$file = $data[key($data)]['file'];
				if($item = item_one($id)) {
					$path = $site['path'].'/media/photo/'.$id.'/';
					if(unlink($path.$file.'.jpg')) {
						thumb_del($file, $id);
						$photo = explode(',', $item['photo']);
						unset($photo[array_search($file, $photo)]);
						
						db_connect()->where('id', $item['id'])->update('item', ['photo' => implode(',', $photo)]);
					}
				}
			}
		} else {
			return false;
		}
	}

	function item_status($data) {
		if(is_array($data)) {
			if(isset($data['id']) && isset($data['user_id'])) {
				$item_id = $data['id'];
				$user_id = $data['user_id'];
				if($user = user_one($user_id)) {
					if($item = item_one($item_id, $user_id)) {
						global $price_ank;

						if($data['action'] == 'top') {

							if($item['status_premium'] == 1) {
								$price_ank['top'] = $price_ank['top']/2;
							}

							if($user['balance'] >= $price_ank['top']) {
								if(user_change_balance($price_ank['top'], $user_id)) {
									$result = [
										'update' => ['date_top' => date('Y-m-d H:i:s')],
										'event' => 'Анкета ID '.$item['id'].' поднята. С баланса списано: '.$price_ank['top'].' рублей.',
										'telegram' => true
									];
								}
							}

						} elseif($data['action'] == 'active') {

							if($item['status_active'] == 0) {
								if($user['balance'] >= $price_ank['activation']+$price_ank['blank']) {
									if(user_change_balance($price_ank['activation']+$price_ank['blank'], $user_id)) {
										$result = [
											'update' => ['status_active' => 1, 'sum' => $price_ank['blank']],
											'event' => 'Анкета ID '.$item['id'].' опубликована. С баланса списано: '.$price_ank['activation']+$price_ank['blank'].' рублей.'
										];
									}
								}
							} elseif($item['status_active'] == 1) {
								$result = [
									'update' => ['status_active' => 0, 'status_vip' => 0, 'status_premium' => 0, 'sum' => 0],
									'event' => 'Анкета ID '.$item['id'].' скрыта.'
								];
							}

						} elseif($data['action'] == 'vip') {

							if($item['status_vip'] == 0) {
								if($user['balance'] >= $price_ank['vip']) {
									if(user_change_balance($price_ank['vip'], $user_id)) {
										$result = [
											'update' => ['status_vip' => 1, 'sum' => $item['sum']+$price_ank['vip']],
											'event' => 'Анкета ID '.$item['id'].' назначен статус &laquo;VIP&raquo;. С баланса списано: '.$price_ank['vip'].' рублей.'
										];
									}
								}
							} elseif($item['status_vip'] == 1) {
								$result = [
									'update' => ['status_vip' => 0, 'status_premium' => 0, 'sum' => $price_ank['blank']],
									'event' => 'Анкета ID '.$item['id'].' снят статус &laquo;VIP&raquo;.'
								];
							}
							
						} elseif($data['action'] == 'premium') {
							
							if($item['status_premium'] == 0) {
								if($user['balance'] >= $price_ank['premium']) {
									if(user_change_balance($price_ank['premium'], $user_id)) {
										$result = [
											'update' => ['status_premium' => 1, 'sum' => $item['sum']+$price_ank['premium']],
											'event' => 'Анкета ID '.$item['id'].' назначен статус &laquo;PREMIUM&raquo;. С баланса списано: '.$price_ank['premium'].' рублей.'
										];
									}
								}
							} elseif($item['status_premium'] == 1) {
								$result = [
									'update' => ['status_premium' => 0, 'sum' => $item['sum']-$price_ank['premium']],
									'event' => 'Анкета ID '.$item['id'].' снят статус &laquo;PREMIUM&raquo;.'
								];
							}

						}

						//update && events && send telegram
						if(isset($result['update'])) {
							$db = db_connect();
							$db->where('id', $item['id'])->update('item', $result['update']);
						}
						if(isset($result['event'])) {
							(new Event($user['id']))->add($result['event']);
						}
						if(isset($result['telegram'])) {
							global $site;
							file_get_contents($site['url'].'/bots/telegram/send.php?type='.$item['type'].'&id='.$item['id']);
						}

					}
				}
			}
		} else {
			return false;
		}
	}

	function item_stat_add($id): void
    {

		$db = db_connect();
		
		$db->where('id', $id)->update('item', ['view_day' => db_connect()->inc(1), 'view_month' => db_connect()->inc(1)]);
	}

	function item_stat_reset($id): void
    {
		
		$stat['view_day'] = 0;
		if(date('d') == '01') {
			$stat['view_month'] = 0;
		}
		db_connect()->where('id', $id)->update('item', $stat);
	}

	function item_one($id, $user_id = false): false|array
    {
		if($id) {
			
			$db = db_connect();

			$db->where('id', $id);
			if($user_id) {
				$db->where('user_id', $user_id);
			}
			return $db->getOne('item');
		} else {
			return false;
		}
	}

	function item_all($user_id = false): array|Generator
    {
		$db = db_connect();
		
		if($user_id) {
			$db->where('user_id', $user_id);
		}
		return $db->get('item');
	}

	function item_all_sum($user_id) {
		if($items = item_all($user_id)) {
			$all_sum = 0;
			foreach($items as $item) {
				if($item['status_active'] == 1) {
					$all_sum += $item['sum'];
				}
			}
			return $all_sum > 0 ? $all_sum : false;
		} else {
			return false;
		}
	}

	function item_all_reset_status($user_id) {
		if($items = item_all($user_id)) {
			foreach($items as $item) {

				$db = db_connect();
				
				$db->where('id', $item['id'])->update('item', [
					'status_active' => 0,
					'status_premium' => 0,
					'status_vip' => 0,
					'sum' => 0
				]);
				return true;
			}
		} else {
			return false;
		}
	}

	function item_abuse($data) {
		if(is_array($data)) {
			if(admin_notification_tg('Жалоба от '.format_phone($data['phone']).' на анкету ID: '.$data['id'].' причина: '.$data['reason'])) {
				setcookie('abuse[item][]', $data['id'], time()+(60*60*24*30*365));
				header("Refresh:0");
			}
		} else {
			return false;
		}
	}