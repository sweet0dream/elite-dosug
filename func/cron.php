<?php
    //cron task
    function cron_task() {
        // change sum
        foreach(user_all() as $user) {
            if($user['balance'] >= 0) {
                if($sum = item_all_sum($user['id'])) {
                    if(user_change_balance($sum, $user['id'])) {
                        //add history change balance
                        (new Event($user['id']))->add('За размещение списано: '.$sum.' рублей, остаток баланса: '.$user['balance']-$sum.' рублей.');
                        notify_sms('Элит Досуг Саратов: За размещение списано '.$sum.' рублей, осталок баланса: '.$user['balance']-$sum.' рублей, этого хватит на '.floor(($user['balance']-$sum)/$sum).' '.format_num(floor(($user['balance']-$sum)/$sum), ['день', 'дня', 'дней']).'. Рекомендуется своевременно пополнять баланс во избежании отключения активных анкет.', $user['phone']);
                    } else {
                        if(item_all_reset_status($user['id'])) {
                            //add history reset status all items
                            (new Event($user['id']))->add('Ваши анкеты скрыты. Недостаточно средств на балансе.');
                            notify_sms('Элит Досуг Саратов: Ваши анкеты скрыты. Недостаточно средств на балансе для оплаты размещения.', $user['phone']);
                        }
                    }
                }
            }
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