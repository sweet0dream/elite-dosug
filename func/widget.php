<?php
    function getTelegramChannelInfo($chatId) {
		global $city;

		$keyCache = $city['domain'] . '-tg';
		$result = (new CacheHelper())->getData($keyCache);

		if (!$result) {
			$getData = (new ClientHelper())->request('notify/telegram/get_channel_info', 'POST', ['chatId' => $city['social']['telegamChannelId']]);
			if ($getData->getCode() != 200) { return; }
			$result = (new CacheHelper())->setData(
				$keyCache,
				$getData->toArray()['channelInfo']
			);
		}

		return $result;
    }