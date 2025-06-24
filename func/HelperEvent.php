<?php

use \ClientHelper as Client;

class EventHelper
{
    private ?int $user_id;
    private Client $client;

    public function __construct(
        ?int $user_id = null
    ) {
        $this->user_id = $user_id;
        $this->client = new Client();
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function add(string $event): bool
    {
        return $this->client->request(
            'user/' . $this->user_id . '/event/add',
            'POST',
            ['event' => $event]
        )->getCode() == 200;
    }

    public function getAll($count = false): array
    {
        return $this->client->request(
            'user/' . $this->user_id . '/event/list',
            'POST',
            $count ? ['count' => $count] : []
        )->toArray();
    }

    public function clear($datetime = false) {
        return $this->client->request(
            'user/' . $this->user_id . '/event/clear',
            'POST',
            $datetime ? ['datetime' => $datetime] : []
        )->getCode() == 200;
    }

}