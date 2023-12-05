<?php
    //route
    $route = array_diff(explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]), array(''));

    require_once('vendor/autoload.php');
    require_once('func/main.php');
    require_once('conf/main.php');

    // Обработка post и files
    if(isset($_POST) && is_array($_POST) && !empty($_POST)) {
        if(isset($_FILES['upload']['tmp_name'])) {
            post($_POST, $_FILES['upload']['tmp_name']);
        } else {
            post($_POST);
        }
    }