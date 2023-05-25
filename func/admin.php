<?php
    function admin_notification_tg($message) {
        $ch = curl_init('https://htmlweb.ru/api/service/tg_send/');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'message' => $_SERVER['HTTP_HOST'].': '.$message,
                'api_key' => '0c2383d6682b17b36138a65ec8b3c2f3'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_getinfo($ch);
            curl_close($ch);
        return curl_getinfo($ch)['http_code'] == 200 ? true : false;
    }