<?php

use \ClientHelper as Client;

class NotifyHelper
{
    private string $senderMs = 'https://sender.elited.ru/';
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
            url:$this->senderMs . 'to_sms',
            method: 'POST',
            param: [
                'phone' => (string) $phone,
                'message' => $text
            ],
            rewriteUrl: true
        )->getCode() === 201;
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
            url:'notify/telegram/send_item_to_channel/' . $itemId,
        )->getCode() == 200;
    }
}