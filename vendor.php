<?php
    //route
    $route = array_diff(explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]), array(''));

    require_once('vendor/autoload.php');
    require_once('func/main.php');

    //load city config
    $city = getCity(explode('.', $_SERVER['HTTP_HOST'])[0]);

    if (is_null($city)) {
        page404();
        die();
    }

    date_default_timezone_set($city['timezone'] ?? 'Europe/Moscow');

    require_once('conf/main.php');

    // Обработка post и files
    if(is_array($_POST) && !empty($_POST)) {
        if(isset($_FILES['upload']['tmp_name'])) {
            post($_POST, $_FILES['upload']['tmp_name']);
        } else {
            post($_POST);
        }
    }