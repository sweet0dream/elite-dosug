<?php
    class Event {
        private $user_id;
        private $table = 'user_events';

        public function __construct(int $user_id) {
            $this->user_id = $user_id;
        }

        public function add(string $event) {
            global $db_connect;
            return $db_connect->insert($this->table, [
                'user_id' => $this->user_id,
                'event' => $event,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        public function getAll($count = false) {
            global $db_connect;
            $db_connect->where('user_id', $this->user_id)->orderBy('created_at', 'DESC');
            if($count) {
                return $db_connect->get($this->table, $count);
            } else {
                return $db_connect->get($this->table);
            }
        }

    }