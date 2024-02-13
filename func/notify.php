<?php
    class Notify {
        private $restLink = 'https://rest.elited.ru/';

        public function sendSms(string $text, int $phone): bool
        {
            return sendPostRequest($this->restLink . 'notify/sms/add', [
                'phone' => $phone,
                'text' => $text
            ])['code'] == 200 ? true : false;
        }

        public function sendItemToTelegramChannel(array $data): bool
        {
            return sendPostRequest($this->restLink . 'notify/telegram/send_item_to_channel', $data)['code'] == 200 ? true : false;
        }
    }