<?php
    //generate thumb
    function thumb($file, $id, $param) {
        if($id == db_connect()->where('id', $id)->getOne('item')['id']) {
            global $site;

            if(!isset($param['width'])) $param['width'] = 0;
            if(!isset($param['height'])) $param['height'] = 0;

            $thumb_path = $site['path'].'/media/photo/'.$id.'/thumb/'.$file.'_'.implode('x', $param).'.webp';
            $thumb_url = '/media/photo/'.$id.'/thumb/'.$file.'_'.implode('x', $param).'.webp';

            if(file_exists($thumb_path)) {
                return $thumb_url;
            } else {
                if(!is_dir($site['path'].'/media/photo/'.$id.'/thumb/')) mkdir($site['path'].'/media/photo/'.$id.'/thumb/');
                if(file_exists($src = $site['path'].'/media/photo/'.$id.'/'.$file.'.jpg')) {
                    $photo = new \claviska\SimpleImage();
                    $photo->fromFile($src);
                    if($param['width'] != 0 && $param['height'] != 0) {
                        $photo->thumbnail($param['width'], $param['height']);
                    } else {
                        if($param['width'] == 0) {
                            $photo->fitToHeight($param['height']);
                        } elseif($param['height'] == 0) {
                            $photo->fitToWidth($param['width']);
                        }
                    }
                    if(isset($param['opacity'])) {
                        $photo->opacity($param['opacity']);
                    }
                    if($photo->toFile($thumb_path, 'image/webp', 100)) {
						return $thumb_url;
					}
                } else {
                    return false;
                }
            }
        }
    }

    //thumb del
    function thumb_del($file, $id) {
        global $site;
        if(is_dir($thumb_dir = $site['path'].'/media/photo/'.$id.'/thumb/')) {
            foreach(scandir($thumb_dir) as $v) {
                if($v != '.' && $v != '..') {
                    if(explode('_', $v)[0] == $file) {
                        $count_remove[] = $site['path'].'/media/photo/'.$id.'/thumb/'.$v;
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