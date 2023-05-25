<?php
    $content = '';
    if(isset($_SESSION['auth']) && isset($_SESSION['auth']['id'])) {
        if($user = db_connect()->where('id', $_SESSION['auth']['id'])->getOne('user')) {
            if($user['id'] == $_SESSION['auth']['id']) {
                if(isset($route[2]) && $route[2] == 'logout') {
                    //logout
                    unset($_SESSION['auth']);
                    $content .= redirect($site['url']);
                }
                $content .= viewUser($user);
            } else {
                $content .= redirect($site['url']);
            }
        } else {
            $content .= redirect($site['url']);
        }
    } else {
        $content .= redirect($site['url']);
    }
?>

<?= $content ?>