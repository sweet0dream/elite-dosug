<?php
    /**
     * @param string $text
     * @param int $phone
     * @return bool
     *
     * @depricated
     *  if(db_connect()->insert('notify_sms', [
     *       'sms_id' => date('YmdHis').rand(111,999),
     *       'phone' => $phone,
     *       'text' => $text
     *  ])) {
     *       return true;
     *  } else {
     *       return false;
     *  }
     *
     */
    function notify_sms(string $text, int $phone): bool
    {
        return file_get_contents(
            'https://rest.elited.ru/notify/sms/add',
            true,
            stream_context_create([
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => json_encode([
                    'phone' => $phone,
                    'text' => $text
                ]),
            ],
        ]));
    }

    function send_item_to_telegram_channel(array $data) {
        return(sendPostRequest('https://rest.elited.ru/notify/telegram/send_item_to_channel', $data));
    }