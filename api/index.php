<?php
    require_once('../vendor.php');
    require_once('functions.php');

    if(isset($route[2])) {
        if(isset($types[$route[2]])) {
            if(isset($route[3])) {
                if(isset(db_connect()->where('type', $route[2])->where('id', $route[3])->getOne('item')['id'])) {
                    if(isset($route[4]) && $route[4] == 'thumb') {
                        if(isset($_GET)) {
                            $json_data = json_one_item_thumb($route[3], $_GET);
                        }
                    } else {
                        $json_data = json_one_item($route[3]);
                    }
                }
            } else {
                if(isset($_GET['vip']) && $_GET['vip'] == 1) {
                    foreach(db_connect()->where('type', $route[2])->where('status_vip', 1)->where('status_active', 1)->orderBy('status_premium','DESC')->orderBy('date_top','DESC')->get('item') as $k => $v) {
                        $json_data[$k] = json_one_item($v['id']);
                    }
                } else {
                    foreach(db_connect()->where('type', $route[2])->where('status_active', 1)->orderBy('date_top','DESC')->get('item') as $k => $v) {
                        $json_data[$k] = json_one_item($v['id']);
                    }
                }
            }
        } elseif($route[2] == 'adv') {
            $json_data = json_adv($banner);
        }
    } else {
        if(isset($_GET['vip']) && $_GET['vip'] == 1) {
            foreach(db_connect()->where('status_vip', 1)->where('status_active', 1)->orderBy('status_premium','DESC')->orderBy('date_top','DESC')->get('item') as $k => $v) {
                $json_data[$k] = json_one_item($v['id']);
            }
        } else {
            foreach(db_connect()->where('status_active', 1)->orderBy('date_top','DESC')->get('item') as $k => $v) {
                $json_data[$k] = json_one_item($v['id']);
            }
        }
    }

    header('Content-type: application/json; charset=utf-8');

    if(isset($json_data)) {
        echo json_encode(['result' => true, 'data' => $json_data], JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
    } else {
        echo json_encode(['result' => false], JSON_UNESCAPED_UNICODE);
    }