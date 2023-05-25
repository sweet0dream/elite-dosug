<?php
    // notify sms
    function notify_sms($text, $phone) {
        /*$ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'api_key' => '0c2383d6682b17b36138a65ec8b3c2f3',
                'to' => '7'.$phone,
                'text' => $text
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 600);
            curl_setopt($ch, CURLOPT_URL, 'http://htmlweb.ru/sendsms/api.php?send_sms&json');
            $result = json_decode(curl_exec($ch), true);
            return true;*/
           
        $data = [
            'phone' => '+7'.$phone,
            'msg' => $text,
            'device' => 331254,
            'token' => '088921bad2a0854a5f4b6003c3ead0bb'
        ];
           
        $curl = curl_init('https://semysms.net/api/3/sms.php');
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);     
        $output = curl_exec($curl);
            curl_close($curl);
        if(json_decode($output, true)['code'] == 0) {
            return true;
        } else {
            return false;
        }
    }