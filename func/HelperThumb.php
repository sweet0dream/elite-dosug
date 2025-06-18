<?php

class ThumbHelper
{
    private DatabaseHelper $db;
    private string $file;
    private int $id;

    public function __construct(
        string $file,
        int $id
    ) {
        $this->file = $file;
        $this->id = $id;
        $this->db = new DatabaseHelper('item');
    }

    public function generate(?array $param = null): string
    {
        if (is_null($param)) {
            return 'https://media.elited.ru/' . $this->id . '/' . $this->file . '.webp';
        }

        if(!isset($param['width'])) $param['width'] = 0;
        if(!isset($param['height'])) $param['height'] = 0;

        return 'https://media.elited.ru/' . $this->id . '/' . implode('x', $param) . '/' . $this->file . '.webp';
    }

    public function remove(): void
    {
        $media = curl_init();
			curl_setopt($media, CURLOPT_URL, 'https://media.elited.ru/' . $this->id . '/' . $this->file);
			curl_setopt($media, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($media, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_exec($media);
			curl_close($media);
			curl_reset($media);
    }
}