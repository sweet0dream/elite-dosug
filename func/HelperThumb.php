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

    public function generate(array $param): string
    {
        if(!isset($param['width'])) $param['width'] = 0;
        if(!isset($param['height'])) $param['height'] = 0;

        return 'https://media.elited.ru/' . $this->id . '/' . implode('x', $param) . '/' . $this->file . '.webp';
    }

    public function remove(): void
    {
        global $site;
        if(is_dir($thumb_dir = $site['path'].'/media/photo/'.$this->id.'/thumb/')) {
            foreach(scandir($thumb_dir) as $v) {
                if($v != '.' && $v != '..') {
                    if(explode('_', $v)[0] == $this->file) {
                        $count_remove[] = $site['path'].'/media/photo/'.$this->id.'/thumb/'.$v;
                    }
                }
            }
            if(is_array($count_remove) && !empty($count_remove)) {
                foreach($count_remove as $v) {
                    unlink($v);
                }
            }
        }
    }
}