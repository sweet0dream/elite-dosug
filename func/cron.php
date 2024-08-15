<?php
    /**
     * @depricated use (new CronHelper({CITY}))->run() --- 15.08.2024;
     */
    //cron task
    function cron_task() {
        foreach ((new ClientHelper())->request('config/get_all_cities')->toArray() as $city) {
            // change sum
            foreach(user_all($city['id']) as $user) {
                if($user['balance'] >= 0) {
                    if($sum = item_all_sum($user['id'])) {
                        if(user_change_balance($sum, $user['id'])) {
                            //add history change balance
                            $text = 'За размещение списано: '.$sum.' рублей, остаток баланса: '.$user['balance']-$sum.' рублей.';
                            (new EventHelper($user['id']))->add($text);
                            (new NotifyHelper())->sendSms(
                                'Элит Досуг '.$city['value'][0].': '.$text.' Этого хватит на '.floor(($user['balance']-$sum)/$sum).' '.format_num(floor(($user['balance']-$sum)/$sum), ['день', 'дня', 'дней']).'. Рекомендуется своевременно пополнять баланс во избежании отключения активных анкет.',
                                $user['phone']
                            );
                        } else {
                            if(item_all_reset_status($user['id'])) {
                                //add history reset status all items
                                $text = 'Ваши анкеты скрыты. Недостаточно средств на балансе.';
                                (new EventHelper($user['id']))->add('Ваши анкеты скрыты. Недостаточно средств на балансе.');
                                (new NotifyHelper())->sendSms(
                                    'Элит Досуг '.$city['value'][0].': '.$text.' Для оплаты размещения необходимо пополнить баланс.',
                                    $user['phone']
                                );
                            }
                        }
                    }
                }
                // clear event more month
                (new EventHelper($user['id']))->clear(getDateTime('-1 month'));
            }
            // !change sum
            // reset stat
            foreach(item_all() as $item) {
                if($item['view_day'] != 0 || $item['view_month'] != 0 ) {
                    item_stat_reset($item['id']);
                }
            }
            // !reset stat
        }
    }