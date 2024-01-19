<?php
    //post
	function admin_post($data): void
    {
		if(is_array($data) && isset($data[key($data)])) {
			if(function_exists('admin_'.key($data))) {
				call_user_func('admin_'.key($data), $data[key($data)]);
			} else {
				die('no exist func: admin_'.key($data).'()');
			}
		}
	}

    function admin_added_balance($data): void
    {
        $_SESSION['response']['added_balance'] = sendPostRequest('https://rest.elited.ru/user/balance/added/', $data);
    }

    function admin_change_status_for_item($data): void
    {
        $id = $data['item_id']; unset($data['item_id']);
        
        $price = $data['price']; unset($data['price']);
        $action = key($data);
        $value = $data[key($data)];

        $request = [
            'price' => $price,
            'action' => $action,
            'value' => $value
        ];

        $_SESSION['response']['change_status'] = sendPostRequest('https://rest.elited.ru/item/'.$id.'/activity', $request);
    }