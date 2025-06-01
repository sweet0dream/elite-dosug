<?php

use \ClientHelper as Client;

class NotifyHelper
{
    private $senderMs = 'https://sender.elited.ru/';
    private Client $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function sendSms(
        string $text,
        int $phone
    ): bool
    {
        return $this->client->request(
            $this->senderMs . 'to_sms',
            'POST',
            [
                'phone' => $phone,
                'message' => $text
            ]
        )->getCode() == 201 ? true : false;
    }

    public function sendSmsForManager(
        string $text
    ): bool
    {
        global $city;
        return $this->sendSms($text, $city['manager']['phone']);
    }

    public function sendItemToTelegramChannel(string $itemId): bool
    {
        return $this->client->request(
            'notify/telegram/send_item_to_channel/' . $itemId,
            'GET'
        )->getCode() == 200 ? true : false;
    }
}