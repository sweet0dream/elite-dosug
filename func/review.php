<?php
    //post
	function review_post($data) {
		if(is_array($data) && isset($data[key($data)])) {
			if(function_exists('review_'.key($data))) {
				call_user_func('review_'.key($data), $data[key($data)]);
			} else {
				die('no exist func: review_'.key($data).'()');
			}
		} else {

		}
	}

	//review_add
	function review_add($data) {
		if(is_array($data)) {
			if(isset($data['captcha_q'])) {
				if(!isset($data['captcha_a']) || array_sum(explode('.', $data['captcha_q'])) != $data['captcha_a']) {
					$error['captcha'] = 1;
				}
				if($data['review'] == '') {
					$error['review'] = 1;		
				}
			}
			if(isset($error)) {
				$_SESSION['review']['response'] = [
					'data' => $data,
					'error' => $error
				];
			} else {
				unset($data['captcha_q']); unset($data['captcha_a']);
				if($id = db_connect()->insert('item_reviews', $data)) {
					(new Notify())->sendSms(
						'Элит Досуг Саратов: Отзыв к анкете ID '.$data['item_id'].': '.$data['review'].'. Оценка: '.$data['rating'].'. Вериф: '.$data['verify'].'.',
						9053242575
					);
					(new Event(item_one($data['item_id'])['user_id']))->add('Добавлен новый отзыв к анкете ID '.$data['item_id']);
					setcookie('review['.$data['item_id'].'][]', $id, time()+(60*60*24*30*365));
					header("Refresh:0");
				}
			}
		} else {
			return false;
		}
	}

	//review_reply
	function review_reply($data) {
		if(is_array($data)) {
			if($data['reply'] != '') {
				$review = review_item_one($data['id']);
				if(!empty($review) && isset($review['id'])) {
					if(strpos($review['review'], '||') !== false) {
						$review['review'] = explode('||', $review['review'])[0];
					}
					db_connect()->where('id', $review['id'])->update('item_reviews', [
						'review' => $review['review'].'||'.$data['reply']
					]);
				}	
			}
		} else {
			return false;
		}
	}

	//review_remove
	function review_remove($data) {
		if(is_array($data)) {
			$user = user_one($data['user_id']);
			if(!empty($user) && isset($user['id'])) {
				$item = item_one($data['item_id']);
				if($item['status_premium'] == 1) {
					if(review_del($data['id'])) {
						(new Event($user['id']))->add('Удален отзыв в премиум анкете ID '.$item['id'].' бесплатно.');
					}
				} else {
					global $price_ank;
					if($user['balance'] >= $price_ank['delete_review']) {
						if(user_change_balance($price_ank['delete_review'], $user['id'])) {
							if(review_del($data['id'])) {
								(new Event($user['id']))->add('Удален отзыв в анкете ID '.$item['id'].'. С баланса списано: '.$price_ank['delete_review'].' рублей.');
							}
						}
					}
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	//review_del
	function review_del($id) {
		$review = review_item_one($id);
		if(!empty($review) && isset($review['id'])) {
			if(isset($review['id']) && $review['id'] == $id) {
				db_connect()->where('id', $review['id'])->delete('item_reviews');
				return true;
			}
		} else {
			return false;
		}
	}

	//review delete all
	function review_del_all($item_id) {
		$reviews = review_item_all($item_id);
		if(empty($reviews)) {
			return true;
		} else {
			foreach($reviews as $review) {
				review_del($review['id']);
			}
			return true;
		}
	}

    //get all review
    function review_item_all($item_id) {
		if($item_id) {
			return db_connect()->where('item_id', $item_id)->orderBy('created_at', 'DESC')->get('item_reviews');
		} else {
            return false;
        }
	}

	//get one review
	function review_item_one($id) {
		if($id) {
			return db_connect()->where('id', $id)->getOne('item_reviews');
		} else {
            return false;
        }
	}

	//render one review
	function review_render_all($item_id) {
		if(is_array($result = review_item_all($item_id)) && !empty($result)) {
			return array_map('review_render_one', $result);
		} else {
            return false;
        }
	}

	//render one review
	function review_render_one($item) {
		if(is_array($item)) {
            return [
				'id' => $item['id'],
				'rating' => $item['rating'],
				'verify' => $item['verify'],
				'date' => format_date($item['created_at']),
				'text' => strpos($item['review'], '||') !== false ? [
					'client' => explode('||', $item['review'])[0],
					'answer' => explode('||', $item['review'])[1]
				] : [
					'client' => $item['review']
					]
				];
		} else {
            return false;
        }
	}