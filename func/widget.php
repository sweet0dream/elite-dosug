<?php
    function getTelegramChannelInfo($chatId) {
        return sendPostRequest('https://rest.elited.ru/notify/telegram/get_channel_info', [
            'chatId' => $chatId
        ]);
    }