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

    //create invoice
    function payment_create_invoice($data) {
        global $site;
        $pay = new \Qiwi\Api\BillPayments('eyJ2ZXJzaW9uIjoiUDJQIiwiZGF0YSI6eyJwYXlpbl9tZXJjaGFudF9zaXRlX3VpZCI6Im9iMnYway0wMCIsInVzZXJfaWQiOiI3OTA1MzI0MjU3NSIsInNlY3JldCI6IjQ1M2E1YjhlNWE0OTc1MTk0Y2VhNGU4OWU4ODQ1NDJmZGU1YzM4ZTNiZmY5OGQ2YjUyN2QwMjdlMWZmMzAxODkifX0=');
        $id_invoice = $pay->generateId();
        $result = $pay->createBill($id_invoice, [
            'amount' => $data['amount'],
            'currency' => 'RUB',
            'comment' => 'Пополнение баланса пользователя с ID '.$data['user_id'].' на '.$data['amount'].' рублей',
            'expirationDateTime' => $pay->getLifetimeByDay(1),
            'customFields' => [
                'themeCode' => 'KYRYLL-KxGg_Y69Sp',
                'user' => $data['user_id']
            ],
            'successUrl' => $site['url'].'/user/?invoice='.$id_invoice
        ]);
        $_SESSION['payment']['link'] = $result['payUrl'];
    }

    //check invoice
    function payment_check_invoice($invoice_id) {
        $pay = new \Qiwi\Api\BillPayments('eyJ2ZXJzaW9uIjoiUDJQIiwiZGF0YSI6eyJwYXlpbl9tZXJjaGFudF9zaXRlX3VpZCI6Im9iMnYway0wMCIsInVzZXJfaWQiOiI3OTA1MzI0MjU3NSIsInNlY3JldCI6IjQ1M2E1YjhlNWE0OTc1MTk0Y2VhNGU4OWU4ODQ1NDJmZGU1YzM4ZTNiZmY5OGQ2YjUyN2QwMjdlMWZmMzAxODkifX0=');
        $result = $pay->getBillInfo($invoice_id);

        $response = [
            'amount' => explode('.', $result['amount']['value'])[0],
            'user_id' => $result['customFields']['user'],
            'invoice' => $invoice_id
        ];

        if($result['status']['value'] == 'WAITING') {
            $response['status'] = 'waiting';
        } elseif($result['status']['value'] == 'PAID') {
            if(user_add_balance($response['amount'], $response['user_id'])) {
                $response['status'] = 'paid';
            } else {
                $response['status'] = 'unpaid';
            }
        }
        return isset($response['status']) ? $response : false; 
    }

    //get link invoice
    function payment_get_link_invoice($invoice_id) {
        global $site;
        $pay = new \Qiwi\Api\BillPayments('eyJ2ZXJzaW9uIjoiUDJQIiwiZGF0YSI6eyJwYXlpbl9tZXJjaGFudF9zaXRlX3VpZCI6Im9iMnYway0wMCIsInVzZXJfaWQiOiI3OTA1MzI0MjU3NSIsInNlY3JldCI6IjQ1M2E1YjhlNWE0OTc1MTk0Y2VhNGU4OWU4ODQ1NDJmZGU1YzM4ZTNiZmY5OGQ2YjUyN2QwMjdlMWZmMzAxODkifX0=');
        return $pay->getPayUrl($pay->getBillInfo($invoice_id), $site['url'].'/user/?invoice='.$invoice_id);
    }