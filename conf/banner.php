<?php
    $banner = [
        'b0' => [ //4500
            ['580858', 'phone' => '+78452580858', 'value' => 'Девочки'],        // Оплата 23 -- 89020405585 / 4000
            ['89053233433', 'phone' => '+79053233433', 'value' => 'Элизиум'],   // Оплата 21 -- 89053233433 / 4000
            ['709090', 'phone' => '+78452709090', 'value' => 'Шарм']            // Оплата 21 -- 89053233433 / 4000
        ],
        'b1' => [ //3500
            ['590160', 'phone' => '+78452590160', 'value' => 'Милана'],         // Оплата 21 -- 89053233433 / 2000
            ['910160', 'phone' => '+78452910160', 'value' => 'Императрица'],    // Оплата 21 -- 89053233433 / 2000
            ['592292', 'phone' => '+78452592292', 'value' => 'Нега'],           // Оплата 21 -- 89053233433 / 2000
            ['256156', 'phone' => '+78452256156', 'value' => 'Досуг'],          // Оплата 21 -- 89053233433 / 2000
            ['903203', 'phone' => '+78452903203', 'value' => 'Элона'],          // Оплата 21 -- 89053233433 / 2000
            //['401016', 'phone' => '+78452401016', 'value' => 'Империя']       // Оплата 21 -- 89053233433 / 2000
            ['89616495201', 'phone' => '+79616495201', 'value' => 'Бархат']     // Оплата 03 -- 89616495201 / 2000
        ], 
        'b2' => [ //2500
        ],
        'b3' => [ //1500
        ]
    ];

    function renderAdv($type) {
        global $banner;
        $view = '
            <div class="row justify-content-md-center g-0 rekl '.$type.'">
        ';
        if(empty($banner[$type])) {
            $banner[$type] = ['blank'];
        } else {
            shuffle($banner[$type]);
        }
        foreach($banner[$type] as $v) {
            if(is_array($v)) {
                $view .= '
                    <div class="col-12 col-lg-4 col-md-6">
                        <div class="ad">
                            <img src="/media/rekl/'.$type.'/elited_'.$v[0].'.gif" alt="'.(isset($v['value']) ? '&laquo;'.$v['value'].'&raquo;' : $v[0]).'" class="img-fluid">
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
                            <img src="/media/rekl/'.$type.'/'.$v.'.gif" alt="Blank" class="img-fluid">
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