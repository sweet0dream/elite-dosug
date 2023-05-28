<?php
    // notify sms
    function notify_sms($text, $phone) {
        if(db_connect()->insert('user_sms', [
            'phone' => $phone,
            'text' => $text
        ])) {
            return true;
        } else {
            return false;
        }
    }

    // get sms & send per day & delete another
    function notify_send() {
        $notifyData = [];
        foreach(db_connect()->get('user_sms') as $v) {
            if(explode(' ', $v['created_at'])[0] == date('Y-m-d')) {
                $notifyData[$v['id']] = [
                    'phone' => '+7'.$v['phone'],
                    'text' => $v['text']
                ];
            } else {
                db_connect()->where('id', $v['id'])->delete('user_sms');
            }         
        }
        return $notifyData;
    }