<?php
    $content = '';
	if(isset($route[2]) && $route[2] != 'p') {
        $itemKey = $city['domain'] . '-item-' . $route[2];
        $item = (new CacheHelper())->getData($itemKey);
        if (!$item) {
            $item = (new CacheHelper())->setData(
                $itemKey,
                (new DatabaseHelper('item'))->fetchOne((int)$route[2])->getResult()
            );
        }
        if(isset($item['id'])) {
            if($route[1] == $item['type']) {
                $content .= viewFull($item);
            } else {
                $content = redirect($site['url'].'/'.$item['type'].'/'.$item['id'].'/');
            }
        }
    } else {
        $current_page = 1;
        if(isset($route[2]) && $route[2] == 'p') {
            if(isset($route[3]) && is_numeric($route[3])) {
                $current_page = $route[3];
                if(isset([0,1][$current_page])) {
                    $content = redirect('/'.$route[1].'/');
                }
            }
        }

        $keySectionItems = $city['domain'] . '-' . $route[1] . '-' . (isset($current_page) ? $current_page : 1);
        $totalPagesKey = $city['domain'] . '-' . $route[1] . '-totalpage';

        $items = (new CacheHelper())->getData($keySectionItems);
        $total_pages = (new CacheHelper())->getData($totalPagesKey);

        if (!$items || !$total_pages) {
            $sectionItems = (new DatabaseHelper('item'))->fetchAll([
				'city_id' => $city['id'],
                'type' => $route[1],
				'status_active' => 1
			], [
				'date_top' => 'DESC'
            ], (isset($current_page) ? $current_page : 1));
            $items = (new CacheHelper())->setData(
                $keySectionItems,
                $sectionItems->getResult()
            );
            $total_pages = (new CacheHelper())->setData(
                $totalPagesKey,
                ['pages' => $sectionItems->getTotalPages()]
            );
        }

        $total_pages = $total_pages['pages'];

        if($total_pages > 1 && empty($items)) {
            $content = redirect('/'.$route[1].'/'.$route[2].'/'.$total_pages.'/');
        } elseif($total_pages == 1 && empty($items)) {
            $content = redirect('/'.$route[1].'/');
        }

        $content .= '
            <div class="card mx-3 my-1">
                <div class="card-body p-2">
                    <h1 class="card-title text-center fs-5">
        ';
        if($route[1] == 'ind') {
            $content .= 'Индивидуалки ' . $city['value'][1] . ' ждут твоего звонка сейчас';
        } elseif($route[1] == 'sal') {
            $content .= 'Интим с проститутками ' . $city['value'][1] . ' из салонов для вас';
        } elseif($route[1] == 'man') {
            $content .= 'Услуги мужского эскорта ' . $city['value'][2] . ' для женщин и мужчин';
        } elseif($route[1] == 'tsl') {
            $content .= 'Трансы ' . $city['value'][1] . ' – лучший экзотический подарок для секса';
        }
        $content .= '
                    </h1>
                    <p class="card-text">
        ';
        if($route[1] == 'ind') {
            $content .= '
                Окунуться в мир страсти и нежности, разврата и похоти. Получить удовольствие от проникновения и минета. Увидеть соблазнительный стриптиз 
                и почувствовать возбуждение от одного взгляда на манящие изгибы тела <b>шлюхи ' . $city['value'][1] . '</b>. Зайдите на наш сайт и посмотрите, какие 
                <b>индивидуалки ' . $city['value'][1] . '</b> предлагают себя. Узнайте, что входит в перечень их умений. Девушки на нашем сайте любят дарить мужчинам 
                удовольствие и потокать всем прихотям. Вам будет, чем заняться в их компании. Ласковые куртизанки подарят вам превосходный досуг. 
                Простая обывательница не способна на такой фейерверк ощущений. Позвоните понравившейся индивидуалке и пригласите ее в гости.
            ';
        } elseif($route[1] == 'sal') {
            $content .= '
                Томные красотки согреют вас в этот вечер и подарят тепло своих вагин. Пусть вы требовательный мужчина, но они удовлетворят все 
                ваши капризы и желания. Для них нет ничего неразрешимого. Любой запрос выполнят без всяких проблем. Каждая просьба и сон будут реализованы 
                почти мгновенно. Пылкий темперамент и изящное тело сведут вас с ума. <b>Проститутки ' . $city['value'][1] . '</b> искупают вас в нежности. Безумная встреча 
                и приятный досуг гарантирован. Доверьтесь им и они отблагодарят вас жарким сексом.
            ';
        } elseif($route[1] == 'man') {
            $content .= '
                Для тех, кто в спб выбирает услуги — мужской эскорт, предлагаем ознакомиться с кандидатами, а возможно даже и целыми агентствами 
                по подбору именно на нашем сайте. Каждая красавица ' . $city['value'][2] . ' достойна быть в близости и сопровождении ровно в той степени, которую она 
                может себе позволить. <b>Мужской эскорт ' . $city['value'][1] . '</b> будет приятным подарком себе на любое мероприятие, будь то девичник, что 
                называется – здесь приемлемы парни по вызову, для выпускного бала или важного вечера, для похода на конференцию, в театр, кино, клуб и 
                так далее, есть даже варианты «муж на час», при этом вовсе не обязательно что выбирать придется «кота в мешке», то есть возможность 
                ознакомиться и даже пообщаться перед нужным событием.
            ';
        } elseif($route[1] == 'tsl') {
            $content .= '
                Для рабынь плотских утех нет запретов и <b>трансы ' . $city['value'][1] . '</b> готовы подставить вам свои манящие попки. Настоящие искусительницы с членом 
                получают истинное удовольствие от анального секса, и поэтому вам не будет скучно в их компании. Не заставляйте их ждать – звоните! Ночные 
                чаровницы позаботятся о том, что бы вы запомнили встречу на долгое время. Вы захотите вернуться транссексуалки ' . $city['value'][1] . ' и позвать на свидание 
                ту, которая принесла столько удовольствия. Реализуйте свои мечты и фантазии в роли пассива и заполните свою попку членом девки с сиськами. 
                Будешь стонать и извивается от удовольствия и оргазма. Набери номер одной из трассексуалок и дайте волю своим эротическим мечтам.
            ';
        }
        $content .= '
                    </p>
                </div>
            </div>
            <div class="row g-0">
	            <div class="col-12">
		            <section class="partIntro">
                        <!--<h3 class="text-end m-0 me-3 pageTitle">'.$types[$route[1]]['names'][2].'</h3>-->
                        '.(!isset([0,1][$current_page]) ? '<hr class="my-1 mx-3" /><p class="text-muted text-end m-0 me-5"><strong>'.$current_page.'</strong> страница</p>' : '').'
			            <div class="row justify-content-md-center g-0">
        ';
        if (isset($items) && count($items) > 0) {
            foreach($items as $item) {
                $content .= '
                            <div class="col-12 col-lg-4 col-md-6">
                                '.viewIntro(item_decode($item)).'
                            </div>
                ';
            }
        } else {
            $content .= '
                            <div class="p-2">
                                <div class="alert alert-warning mx-2">
                                    <p class="text-center mb-2">
                                        <img src="/assets/images/nothing.webp" class="img-thumbnail">
                                    </p>
                                    <p class="text-center m-0">К сожалению в разделе отсутствуют активные анкеты...</p>
                                </div>
                            </div>
            ';
        }
        $content .= '
                        </div>
        ';
        if($total_pages > 1) {
            $content .= '
                        <ul class="pagination my-1 justify-content-center">
            ';
            for($i = 1; $i <= $total_pages; $i++) {
                $content .= '
                            <li class="page-item'.($current_page == $i ? ' disabled' : '').'"><a class="page-link" href="/'.$route[1].'/p/'.$i.'/">'.$i.'</a></li>
                ';
            }
            $content .= '
                        </ul>
            ';
        }
        $content .= '
                    </section>
                </div>
            </div>
        ';
    }
?>

<?= $content ?>