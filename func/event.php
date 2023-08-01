<?php
    class Event {
        private $user_id;
        private $table = 'user_events';

        public function __construct(int $user_id) {
            $this->user_id = $user_id;
        }

        public function add(string $event) {
            return db_connect()->insert($this->table, [
                'user_id' => $this->user_id,
                'event' => $event,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        public function getAll($count = false) {
            $result = db_connect()->where('user_id', $this->user_id)->orderBy('created_at', 'DESC');
            if($count) {
                return $result->get($this->table, $count);
            } else {
                return $result->get($this->table);
            }
        }

        public function clear($date = false) {
            $ids = [];
            $currentDate = $date ? strtotime($date) : strtotime(date('Y-m-d H:i:s'));
            foreach($this->getAll() as $i) {
                if($currentDate > strtotime($i['created_at'])) {
                    $ids[] = $i['id'].' '.$i['created_at'];
                }
            }
            if(!empty($ids)) {
                foreach($ids as $deleteEventId) {
                    db_connect()->where('id', $deleteEventId)->delete($this->table);
                }
            }
            return true;
        }

    }