<?php
    class Event {
        private $restLink = 'https://rest.elited.ru/';
        private $user_id;

        public function __construct(int $user_id) {
            $this->user_id = $user_id;
        }

        public function add(string $event): bool
        {
            return sendPostRequest($this->restLink . 'user/' . $this->user_id . '/event/add', [
                'event' => $event
            ])['code'] == 200 ? true : false;
        }

        public function getAll($count = false) {
            $response = sendPostRequest($this->restLink . 'user/' . $this->user_id . '/event/list', $count ? [
                'count' => $count
            ] : []);
            return $response['code'] == 200 ? $response['data'] : false;
        }

        public function clear($datetime = false) {
            return sendPostRequest($this->restLink . 'user/' . $this->user_id . '/event/clear', $datetime ? [
                'datetime' => $datetime
            ] : [])['code'] == 200 ? true : false;
        }

    }