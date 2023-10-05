<?php
    // notify sms
    function notify_sms($text, $phone) {
        if(db_connect()->insert('notify_sms', [
            'sms_id' => date('YmdHis').rand(111,999),
            'phone' => $phone,
            'text' => $text
        ])) {
            return true;
        } else {
            return false;
        }
    }