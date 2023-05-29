<?php
    require_once('func/main.php');
    require_once('conf/main.php');
    require_once('vendor/autoload.php');
    echo json_encode(notify_send(), JSON_UNESCAPED_UNICODE);