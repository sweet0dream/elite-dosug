<?php
    require_once('vendor.php');
    echo json_encode(notify_send(), JSON_UNESCAPED_UNICODE);