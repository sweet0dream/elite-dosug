<?php
    global $site;
    $content = '';
    if(isset($_SESSION['auth']['id'])) {
        $user = new DatabaseHelper('user')->fetchOne($_SESSION['auth']['id'])->getResult();
        if($user) {
            if($user['id'] == $_SESSION['auth']['id']) {
                if(isset($route[2]) && $route[2] == 'logout') {
                    //logout
                    unset($_SESSION['auth']);
                    $content .= redirect($site['url']);
                }
                if (isset($_SESSION['auth']['type'])) {
                    $content .= match ($_SESSION['auth']['type']) {
                        'reg' => viewUser($user),
                        'man' => viewManager($user)
                    };
                }
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