<?php
    function renderAdv($section): string
    {
        global $site, $city;
        $cityPath = $site['path'].'/media/'.$city['name'].'/';
        $banner = [];
        if (is_dir($cityPath)) {
            foreach (scandir($cityPath) as $file) {
                if (!in_array($file, ['.', '..']) && $section == explode('_', $file)[0]) {
                    $banner[] = [
                        'phone' => '+7' . explode('_', explode('.', $file)[0])[1],
                        'file' => '/media/' . $city['name'] . '/' . $file
                    ];
                }
            }
        }

        $view = '
            <div class="row justify-content-md-center g-0 rekl '.$section.'">
                <div class="col-12 col-lg-4 col-md-6">
                    <div class="ad">
        ';
        if (empty($banner)) {
            $view .= '
                        <img src="/media/default/'.$section.'.gif" alt="Adv" class="img-fluid">
            ';
        } else {
            shuffle($banner);
            foreach ($banner as $v) {
                $view .= '
                        <img src="'.$v['file'].'" alt="Adv" class="img-fluid">
                ';
                if(isMobile()) {
                    $view .= '
                        <div class="hov d-flex justify-content-center align-items-center">
                            <a href="tel:'.$v['phone'].'" class="btn btn-danger">Позвонить '.$v['phone'].'</a>
                        </div>
                    ';
                }
            }
        }
        $view .= '
                    </div>
                </div>
            </div>
        ';

        return $view;
    }