<?php

    //post
	function payment_post($data) {
		if(is_array($data) && isset($data[key($data)])) {
			if(function_exists('payment_'.key($data))) {
				call_user_func('payment_'.key($data), $data[key($data)]);
			} else {
				die('no exist func: payment_'.key($data).'()');
			}
		} else {
            return false;
		}
	}