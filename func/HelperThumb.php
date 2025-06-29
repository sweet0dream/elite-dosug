<?php

class ThumbHelper
{
    private string $file;
    private int $id;

    public function __construct(
        string $file,
        int $id
    ) {
        $this->file = $file;
        $this->id = $id;
    }

    public function generate(?array $param): string
    {
        switch (true) {
            case !isset($param['width']):
                $param['width'] = 0;
                break;
            case !isset($param['height']):
                $param['height'] = 0;
                break;
        }

        return 'https://media.elited.ru/' . $this->id . '/' . implode('x', $param) . '/' . $this->file . '.webp';
    }

    public function view(): string
    {
        return 'https://media.elited.ru/' . $this->id . '/' . $this->file . '.webp';
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