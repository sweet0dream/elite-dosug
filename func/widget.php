<?php
    function getTelegramChannelInfo($chatId) {
		global $city;

		$keyCache = $city['domain'] . '-tg';
		$result = (new CacheHelper())->getData($keyCache);

		if (!$result) {
			$getData = (new ClientHelper())->request('notify/telegram/get_channel_info', 'POST', ['chatId' => $city['social']['telegamChannelId']])->toArray();
			$result = (new CacheHelper())->setData(
				$keyCache,
				$getData['channelInfo']
			);
		}

		return $result;
    }