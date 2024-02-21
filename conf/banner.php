<?php
    function renderAdv($section) {

        $banner[$section] = json_decode(file_get_contents('https://rest.elited.ru/v1/adv/'), true)['data'][$section];

        $view = '
            <div class="row justify-content-md-center g-0 rekl '.$section.'">
        ';
        if(empty($banner[$section])) {
            $banner[$section] = ['blank'];
        } else {
            shuffle($banner[$section]);
        }
        foreach($banner[$section] as $v) {
            if(is_array($v)) {
                $view .= '
                    <div class="col-12 col-lg-4 col-md-6">
                        <div class="ad">
                            <img src="'.$v[0].'" alt="'.(isset($v['value']) ? '&laquo;'.$v['value'].'&raquo;' : $v[0]).'" class="img-fluid">
                ';
                if(isMobile()) {
                    $view .= '
                            <div class="hov d-flex justify-content-center align-items-center">
                                <a href="tel:'.$v['phone'].'" class="btn btn-danger">Позвонить '.(isset($v['value']) ? 'в &laquo;'.$v['value'].'&raquo;' : $v[0]).'</a>
                            </div>
                    ';
                }
                $view .= '
                        </div>
                    </div>
                ';
            } else {
                $view .= '
                    <div class="col-12 col-lg-4 col-md-6">
                        <div class="ad">
                            <img src="/media/rekl/'.$section.'/'.$v.'.gif" alt="Blank" class="img-fluid">
                        </div>
                    </div>
                ';
            }
        }
        $view .= '
            </div>
        ';
        return $view;
    }