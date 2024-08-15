<?php
    //post
	function manager_post($data): void
    {
        global $city;
		if(is_array($data) && isset($data[key($data)])) {
			if(function_exists('manager_'.key($data))) {
                (new CacheHelper())->dropCacheCity($city['domain']);
				call_user_func('manager_'.key($data), $data[key($data)]);
			} else {
				die('no exist func: manager_'.key($data).'()');
			}
		}
	}

    function manager_added_balance($data): void
    {
        $request = (new ClientHelper())->request(
            'user/balance/added',
            'POST',
            $data
        );

        $_SESSION['response']['added_balance'] = [
            'code' => $request->getCode(),
            'data' => $request->toArray()
        ];
    }

    function manager_change_status_for_item($data): void
    {
        $id = $data['item_id']; unset($data['item_id']);
        
        $price = $data['price']; unset($data['price']);
        $action = key($data);
        $value = $data[key($data)];

        $param = [
            'price' => $price,
            'action' => $action,
            'value' => $value
        ];

        $request = (new ClientHelper())->request(
            'item/' . $id . '/activity',
            'POST',
            $param
        );

        $_SESSION['response']['change_status'] = [
            'code' => $request->getCode(),
            'data' => $request->toArray()
        ];
    }