<?php
    function json_one_item($id) {
        global $types, $rao;

        $item = item_decode(db_connect()->where('id', $id)->getOne('item'));

        $item['meta'] = $types[$item['type']]['names'];

        $item['phone'] = format_phone($item['phone']);

        $type = $types[$item['type']];

        foreach(['user_id', 'sum'] as $v) { unset($item[$v]); }

        $item['type'] = [
            'key' => $item['type'],
            'value' => $type['names'][0]
        ];

        foreach($item['info'] as $k => $v) {
            $item['info'][$k] = [
                'name' => $type['fields']['info'][$k]['name'],
                'value' => isset($type['fields']['info'][$k]['value']) ? $type['fields']['info'][$k]['value'][$v] : $v
            ];
        }

        foreach($type['fields']['service'] as $k => $v) {
            $item['service'][$k]['name'] = $v['name'];
            foreach($v['value'] as $kv => $vv) {
                $item['service'][$k]['value'][$kv]['name'] = $vv;
                if(in_array($kv, $item['service'][$k])) {
                    $item['service'][$k]['value'][$kv]['turn'] = 1;
                    unset($item['service'][$k][array_search($kv, $item['service'][$k])]);
                } else {
                    $item['service'][$k]['value'][$kv]['turn'] = 0;
                }
            }
        }

        foreach($item['price'] as $k => $v) {
            if($v != '') {
                $item['price'][$k] = [
                    'name' => $type['fields']['price'][$k]['name'],
                    'value' => $v
                ];
            } else {
                unset($item['price'][$k]);
            }
        }

        $item['rao'] = $rao[$item['rao']];

        foreach(['add', 'edit', 'top'] as $date) {
            $item['date'][$date] = format_date($item['date_'.$date]); unset($item['date_'.$date]);
        }
        foreach(['active', 'premium', 'vip', 'real'] as $status) {
            $item['status'][$status] = $item['status_'.$status]; unset($item['status_'.$status]);
        }
        foreach(['day', 'month'] as $view) {
            $item['view'][$view] = $item['view_'.$view]; unset($item['view_'.$view]);
        }
        $item['photo'] = explode(',', $item['photo']); shuffle($item['photo']);
        foreach($item['photo'] as $key => $photo) {
            //$item['photo'][$key] = '/media/photo/'.$item['id'].'/'.$photo.'.jpg';
            $item['photo'][$key] = thumb($photo, $item['id'], ['width' => 500]);
        }

        if(review_render_all($item['id']) != false) {
            $reviews = review_render_all($item['id']);
            $item['reviews']['count'] = count($reviews).' '.format_num(count($reviews), ['отзыв', 'отзыва', 'отзывов']);
            $item['reviews']['value'] = $reviews;
        }

        return $item;
    }

    function json_one_item_thumb($id, $param) {
        foreach(explode(',', item_decode(db_connect()->where('id', $id)->getOne('item'))['photo']) as $file) {
            $photo[] = thumb($file, $id, $param);
        }
        if(isset($photo) && is_array($photo)) {
            return $photo;
        }
    }

    function json_adv($data) {
        global $site;
        $prefix['default'] = 'elited';
        if(isset($_GET['prefix'])) {
            $prefix['domain'] = $_GET['prefix'];
        }
        foreach($data as $k => $v) {
            if(!empty($v) && is_array($v)) {
                for($i = 0; $i < count($v); $i++) {
                    if(isset($prefix['domain']) && file_exists($site['path'].'/media/rekl/'.$k.'/'.$prefix['domain'].'_'.$data[$k][$i]['0'].'.gif')) {
                        $data[$k][$i]['0'] = '/media/rekl/'.$k.'/'.$prefix['domain'].'_'.$data[$k][$i]['0'].'.gif';
                    } else {
                        $data[$k][$i]['0'] = '/media/rekl/'.$k.'/'.$prefix['default'].'_'.$data[$k][$i]['0'].'.gif';
                    }
                }
            }
        }
        return $data;
    }