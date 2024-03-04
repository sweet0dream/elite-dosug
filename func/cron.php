<?php
    //cron task
    function cron_task() {
        global $city;
        // change sum
        foreach(user_all() as $user) {
            if($user['balance'] >= 0) {
                if($sum = item_all_sum($user['id'])) {
                    if(user_change_balance($sum, $user['id'])) {
                        //add history change balance
                        $text = 'За размещение списано: '.$sum.' рублей, остаток баланса: '.$user['balance']-$sum.' рублей.';
                        (new Event($user['id']))->add($text);
                        (new Notify())->sendSms(
                            'Элит Досуг '.$city['value'][0].': '.$text.' Этого хватит на '.floor(($user['balance']-$sum)/$sum).' '.format_num(floor(($user['balance']-$sum)/$sum), ['день', 'дня', 'дней']).'. Рекомендуется своевременно пополнять баланс во избежании отключения активных анкет.',
                            $user['phone']
                        );
                    } else {
                        if(item_all_reset_status($user['id'])) {
                            //add history reset status all items
                            $text = 'Ваши анкеты скрыты. Недостаточно средств на балансе.';
                            (new Event($user['id']))->add('Ваши анкеты скрыты. Недостаточно средств на балансе.');
                            (new Notify())->sendSms(
                                'Элит Досуг '.$city['value'][0].': '.$text.' Для оплаты размещения необходимо пополнить баланс.',
                                $user['phone']
                            );
                        }
                    }
                }
            }
            // clear event more month
            (new Event($user['id']))->clear(date('Y-m-d H:i:s', strtotime('-1 months')));
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