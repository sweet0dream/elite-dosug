<?php

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CronHelper
{
    private array $cities;
    private array $city;
    private Logger $log;

    public function __construct(array $cities)
    {
        $this->cities = $cities;
    }

    public function run(): void
    {
        $now = new DateTimeImmutable('now');
        foreach ($this->cities as $city) {
            $this->city = $city;
            $this->log = (new Logger('cron', [
                new StreamHandler('log/cron/' . $now->format('Y') . '/' . $now->format('m') . '/' . $now->format('d') . '/' . $city['name'] . '.log')
            ]));
            $this->process();
        }
    }

    private function process(): void
    {
        $startDate = new DateTimeImmutable('now');
        $this->log->info('Start cron task: ' . $startDate->format('d.m.Y H:i:s'));
        foreach (array_filter(array_map(
            fn($user) => $this->validateUser($user) ? $user : null, 
            user_all($this->city['id'])
        )) as $validUser) {
            $changeSum = item_all_sum($validUser['id'], $this->city['id']);
            if ($changeSum > 0 && user_change_balance($changeSum, $validUser['id'])) {
                $residualBalance = $validUser['balance']-$changeSum;
                $textEvent = sprintf(
                    'За размещение списано: %s рублей, остаток баланса: %s рублей.',
                    $changeSum,
                    $residualBalance
                );
                $textSms = sprintf(
                    'Элит Досуг %s: %s Этого хватит на %s. Рекомендуется своевременно пополнять баланс во избежании отключения активных анкет.',
                    $this->city['value'][0],
                    $textEvent,
                    floor($residualBalance/$changeSum).' '.format_num(floor($residualBalance/$changeSum), ['день', 'дня', 'дней'])
                );
                $this->log->info(sprintf(
                    'User #%s changed: {%s}, residual: {%s}. Send sms notify to: %s',
                    $validUser['id'],
                    $changeSum,
                    $residualBalance,
                    $validUser['phone']
                ));
            } else {
                if(item_all_reset_status($validUser['id'])) {
                    $textEvent = 'Ваши анкеты скрыты. Недостаточно средств на балансе.';
                    $textSms = sprintf(
                        'Элит Досуг %s: %s Для оплаты размещения необходимо пополнить баланс.',
                        $this->city['value'][0],
                        $textEvent
                    );
                    $this->log->info(sprintf(
                        'User #%s items hidden',
                        $validUser['id']
                    ));
                }
            }
            if (isset($textEvent)) {
                (new EventHelper($validUser['id']))->add($textEvent);
            }
            if (isset($textSms)) {
                (new NotifyHelper())->sendSms($textSms, $validUser['phone']);
            }
        }
        $this->log->info('Executed time: ' . (new DateTimeImmutable('now'))->diff($startDate)->format('%s') . ' seconds');
    }

    private function validateUser(array $user): bool
    {
        foreach ($this->conditionsUser($user) as $message => $result) {
            if ($result) {
                $this->log->notice('User #' . $user['id'] . ': '. $message, [
                    'user' => [
                        'id' => $user['id'],
                        'balance' => $user['balance']
                    ],
                    'items' => [
                        'active' => item_has_active($user['id']),
                        'sum' => item_all_sum($user['id'])
                    ]
                ]);
                return false;
            }
        }

        return true;
    }

    private function conditionsUser(array $user): Iterator
    {
        yield 'hasn\'t items active' => !item_has_active($user['id'], $this->city['id']);
        yield 'not enough to pay' => $user['balance'] > 0 && $user['balance'] < item_all_sum($user['id']);
    }
}