<?php
	function viewVip($post) {
		global $site, $types;
		//Формирование ссылки
		$url = $site['url'].'/'.$post['type'].'/'.$post['id'].'/';
		//Формирование фото
		$photos = explode(',', $post['photo']); shuffle($photos);
		$photo = (new ThumbHelper($photos[0], $post['id']))->generate(['width' => 300]);
		//Формирование возраста
		$year = '';
		if($post['type'] == 'ind' || $post['type'] == 'man' || $post['type'] == 'tsl') {
			$year .= ', '.$types[$post['type']]['fields']['info']['year']['value'][$post['info']['year']];
		}
		
		$view = '
				<div class="item" style="background-image: url(\''.$photo.'\')" onclick="location.href=\''.$url.'\';">
					<div class="position-absolute top-0 start-0 text-white p-2 icon">
						<i class="fa-solid fa-'.($post['status_premium'] == 1 ? 'crown' : 'star').'"></i>
					</div>
		';
		if($post['status_real'] == 1) {
			$view .= '
					<div class="position-absolute top-50 start-50 translate-middle text-white realy">
						<div class="row g-0 mx-1">
							<div class="col-4 d-flex justify-content-center align-items-center">
								<i class="fa-solid fa-camera"></i>
							</div>
							<div class="col-8">
								<p class="m-0 text-uppercase text-center">Реальное <span>фото</span></p>
							</div>
						</div>
					</div>
			';
		}
		$view .= '
					<a href="'.$url.'" class="title">
						<h4><span class="type">'.$types[$post['type']]['names'][0].'</span> <br><span class="name">'.$post['info']['name'].'</span>'.$year.'</h4>
					</a>
					<div class="info">
		';
		if($post['date_top'] > getDateTime('-2 days')) {
			$view .= '
						<p class="my-1 text-center">
							<span class="font2 text-uppercase badge text-bg-success wait_call">
								<i class="fa-solid fa-phone-volume opacity-50"></i> Жду звонка
							</span>
						</p>
			';
		}
		$view .= '
						<h6 class="my-1">'.(isset($post['price']['express']) && $post['price']['express'] != '' ? 'Экспресс '.$post['price']['express'].' руб' : $post['price']['onehour'].' руб за час').'</h6>
						'.(isMobile() ? '<div class="m-1"><a href="tel:+7'.$post['phone'].'" class="btn btn-light btn-sm w-100 phone"><i class="fa-solid fa-mobile"></i> Звонок</a></div>' : '<p class="mb-1 phone">+7'.$post['phone'].'</p>').'
					</div>
				</div>
		';
		return $view;
	}

	function viewIntro($post) {
		global $site, $types, $city;

		$photos = explode(',', $post['photo']);

		$url = $site['url'].'/'.$post['type'].'/'.$post['id'].'/';
		$urlAlt = $types[$post['type']]['names'][0].' '.$post['info']['name'];
		
		$schema = '
			<script type="application/ld+json">
				{
					"@context": "https://schema.org/", 
					"@type": "Product", 
					"name": "'.$post['info']['name'].'",
					"image": "'.(new ThumbHelper($photos[0], $post['id']))->generate(['width' => 700]).'",
					"description": "'.$post['dopinfo'].'",
					"brand": {
						"@type": "Brand",
						"name": "'.$types[$post['type']]['names'][0].'"
					},
					"sku": "'.$post['id'].'",
					"offers": {
						"@type": "Offer",
						"url": "'.$url.'",
						"priceCurrency": "RUB",
						"price": "'.$post['price']['onehour'].'"
					}
				}
			</script>		
		';
		
		shuffle($photos);

		$name = '<span class="type">'.$types[$post['type']]['names'][0].'</span> <span class="name">'.$post['info']['name'].'</span>';

		unset($post['info']['name']);
		
		if($post['type'] != 'sal') {
			foreach($post['info'] as $k => $v) {
				$top[] = '<div class="pr '.$k.'">'.str_replace('.', '', $types[$post['type']]['fields']['info'][$k]['value'][$v]).'</div>';
			}
		}

		$view = $schema;

		$view .= '
			<div class="card m-1 item">
				<div class="row g-0">
		  			<div class="col-lg-4">
						<div class="inner_photo">
		';
		if($post['date_top'] > getDateTime('-2 days')) {
			$view .= '
							<div class="now"><i>&#9829;</i> онлайн</div>
			';
		}
		$view .= '
							<div class="price">
								<h6>'.$post['price']['onehour'].' за час</h6>
							</div>
		';
		if (isMobile()) {
			$view .= '
							<a href="'.$site['url'].'/'.$post['type'].'/'.$post['id'].'/" alt="'.$urlAlt.'">
								<img src="'.(new ThumbHelper($photos[0], $post['id']))->generate(['width' => 700]).'" class="img-fluid">
							</a>
			';
		} else {
			$view .= '
							<a href="'.$site['url'].'/'.$post['type'].'/'.$post['id'].'/" alt="'.$urlAlt.'" class="photo" style="background-image: url(\''.(new ThumbHelper($photos[0], $post['id']))->generate(['width' => 700, 'height' => 700]).'\')"></a>
			';
		}
		$view .= '
						</div>
		  			</div>
		  			<div class="col-lg-8">
						<div class="card-body p-2">
			  				<h5 class="card-title title">
                                <a href="'.$url.'" title="'.$urlAlt.'">'.$name.'</a>
                            </h5>
			  				'.(isset($top) && is_array($top) ? '<div class="info">'.implode($top).'</div>' : '').'
			  				<p class="card-text m-0 text">'.$post['dopinfo'].'</p>
						</div>
		  			</div>
				</div>
	  		</div>
		';
		return $view;
	}

	function viewFull($post) {
		$post = item_decode($post);
		if($post['status_active'] == 1) item_stat_add($post['id']);

		global $city, $types, $rao;
		$photos = explode(',', $post['photo']); shuffle($photos);
		$view = '
			<div class="row justify-content-md-center g-0 full">
				<div class="col-12 col-lg-10">
					<div class="card my-1">
						<div class="card-header">
							<div class="row g-2">
								<div class="col-2">
									<small class="text-muted opacity-25">#'.$post['id'].'</small>
								</div>
								<div class="col-10">
									<p class="m-0">'.$types[$post['type']]['names'][0].' <b>'.$post['info']['name'].($post['status_real'] == 1 ? ' <span class="text-danger"><b>100%</b> реальное фото</span>' : '').'</b></p>
								</div>
							</div>
							
						</div>
						<div class="card-body p-0">
			';
			if (isMobile() && $post['status_active'] == 1) {
				$view .= '
											
							<div class="alert alert-primary text-center p-1 m-1 fixed-bottom">
								<a href="#" class="text-decoration-none d-block mb-1 fs-4"><i class="fa-solid fa-phone"></i> '.format_phone($post['phone']).'</a>
								<p class="mb-1"><small>...звоню с сайта Элит Досуг '.$city['value'][0].'...</small></p>
								<a href="tel:'.format_phone($post['phone']).'" class="btn btn-primary w-100"><i class="fa-solid fa-mobile"></i> Позвонить сейчас</a>
							</div>
				';
			}
			$view .= '
							<div class="row g-0">
								<div class="col-12 col-lg-3 d-flex align-items-stretch">
									<div class="p-1 d-flex align-items-center" style="background-image: url(\''.(new ThumbHelper($photos[0], $post['id']))->generate(['width' => 700, 'opacity' => 0.35]).'\'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
										<div class="row justify-content-md-center g-2">
			';
			foreach($photos as $photo) {
				$view .= '
											<div class="col-12 col-md-3 col-lg-6 d-flex justify-content-center">
												<a href="/media/photo/'.$post['id'].'/'.$photo.'.jpg" class="w-100">
													<img src="'.(new ThumbHelper($photo, $post['id']))->generate(isMobile() ? ['width' => 700] : ['width' => 700, 'height' => 700]).'" class="border border-2 rounded-4 w-100">
												</a>
											</div>
				';
			}
			$view .= '
										</div>
									</div>
								</div>
								<div class="col-12 col-lg-9 d-flex align-items-center">
									<div class="p-1 w-100">
										<div class="row g-2 mb-2">
											<div class="col-12 col-md-4 d-flex align-items-center">
												<div class="w-100">
													<div class="alert alert-secondary mb-1 p-2 text-center">
														<i class="fa-solid fa-location-dot"></i> '.$rao[$post['rao']].'
													</div>
			';
			if (!isMobile()) {
				$view .= '
													<div class="alert alert-info d-flex align-items-center justify-content-center mb-0 w-100 p-2">
														<div class="m-0 w-100 text-center">
															'.($post['status_active'] == 1 ? '<i class="fa-solid fa-phone opacity-25"></i> <b>'.format_phone($post['phone']).'</b>' : '<b>Номер телефона скрыт</b>').'
															<hr class="border border-info border-2 opacity-25 my-2" />
															<span class="opacity-50"><i>Сообщите нашли анкету на сайте Элит Досуг '.$city['value'][0].'</i></span>
														</div>
													</div>
				';
			}
			$view .= '
												</div>
											</div>
											<div class="col-12 col-md-4 d-flex align-items-center">
												<div class="w-100">
			';
			unset($post['info']['name']);
			foreach($post['info'] as $k => $v) {
				$view .= '
													<div class="row g-1 w-100">
														<div class="col-5 text-end">
															<span class="text-muted">'.$types[$post['type']]['fields']['info'][$k]['name'].':</span>
														</div>
														<div class="col-7 text-start">
															<b>'.$types[$post['type']]['fields']['info'][$k]['value'][$v].'</b>
														</div>
													</div>
				';
			}
			$view .= '
												</div>
											</div>
											<div class="col-12 col-md-4 d-flex align-items-center">
												<div class="w-100">
			';
			foreach($post['price'] as $k => $v) {
				if($v != '') {
					$view .= '
													<div class="row g-1 w-100">
														<div class="col-5 text-end">
															<span class="text-muted">'.$types[$post['type']]['fields']['price'][$k]['name'].':</span>
														</div>
														<div class="col-7 text-start">
															<b>'.$v.' рублей</b>
														</div>
													</div>
					';
				}
			}
			$view .= '
												</div>
											</div>
										</div>
										<div class="alert alert-secondary mb-2 p-2" role="alert">
											<p class="m-0">&laquo;'.$post['dopinfo'].'&raquo;</p>
										</div>
										<div class="row justify-content-md-center g-1 service">
			';
			foreach($types[$post['type']]['fields']['service'] as $k => $v) {
				if(isset($post['service'][$k])) {
				$view .= '
											<div class="col-12 col-md-6 col-lg-4">
												<div class="card p-1 part '.$k.'">
													<div class="name"><b>'.$v['name'].'</b></div>
				';
				foreach($v['value'] as $ik => $iv) {
					$view .= sprintf(
						isset($post['service'][$k]) && in_array($ik, $post['service'][$k])
						? '<p class="m-0 i"><b>%s</b></p>'
						: '<p class="m-0 i text-muted opacity-25"><s>%s</s></p>',
						ucfirst($iv)
					);
				}
				$view .= '
												</div>
											</div>
				';
				}
			}
			$view .= '
										</div>
										<div class="alert alert-dark mt-1 mb-0 p-1 reviews">
			';
			$reviews = review_render_all($post['id']);
			if(isset($reviews) && !empty($reviews) && isset($reviews[0])) {
				$view .= '
											<div class="d-flex justify-content-between">
												<div class="d-flex align-items-center">
													<p class="mb-1 text-center w-100"><b>Отзывы клиентов</b></p>
												</div>
												<div class="d-flex align-items-center">
				';
				if(isset($_COOKIE['auth']['login']) || isset($_SESSION['auth'])) {
					$view .= '
													<button type="button" class="btn btn-danger btn-sm" disabled><i class="fa-solid fa-comment-slash"></i> Вам запрещено оставлять отзывы</button>
					';
				} else {
					if(isset($_COOKIE['review'][$post['id']])) {
						$view .= '
													<button type="button" class="btn btn-success btn-sm" disabled>Вы уже оставили отзыв</button>
						';
					} else {
						$view .= '
													<button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#add_review'.$post['id'].'"><i class="fa-solid fa-comment"></i> Добавить отзыв</button>
						';
					}
				}
				$view .= '
												</div>
											</div>
				';
				foreach($reviews as $review) {
					if($review['rating'] < 2) {
						$rating = 'danger';
					} elseif($review['rating'] >= 2 && $review['rating'] < 4) {
						$rating = 'warning';
					} elseif($review['rating'] >= 4) {
						$rating = 'success';
					}
					$view .= '
											<div class="row g-2">
												<div class="col-4 d-flex align-items-center">
													<div class="w-100">
														<p class="m-0 text-'.$rating.' text-center">
					';
					for($i = 1; $i <= 5; $i++) {
						if($i <= $review['rating']) {
							$view .= '
															<i class="fa-solid fa-star"></i>
							';
						} else {
							$view .= '
															<i class="fa-regular fa-star"></i>
							';
						}
					}
					unset($i);
					$view .= '
														</p>
														<p class="m-0 text-muted text-center">'.$review['date'].'</p>
													</div>
												</div>
												<div class="col-8 d-flex align-items-center">
													<div class="alert alert-light p-1 my-1 w-100 item">
														<p class="m-1">'.($review['verify'] == 1 ? '<i class="fa-solid fa-thumbs-up text-success"></i>' : '<i class="fa-solid fa-thumbs-down text-danger"></i>').' <b>Информация в анкете'.($review['verify'] != 1 ? ' НЕ' : '').' соответствует действительности.</b></p>
														<p class="m-0">'.$review['text']['client'].'</p>
														'.(isset($review['text']['answer']) ? '<hr class="my-1"/><p class="m-0"><strong class="text-danger">Ответ:</strong> '.$review['text']['answer'].'</p>' : '').'
			  										</div>
												</div>
											</div>
					';
				}
			} else {
				$view .= '
											<p class="m-0 text-center">
												'.(isset($_COOKIE['auth']['login']) || isset($_SESSION['auth']) ? '<p class="my-3 text-muted text-center">Рекламодателям запрещено оставлять отзывы.</p>' : '<button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#add_review'.$post['id'].'"><i class="fa-solid fa-comment"></i> Добавить первый отзыв</button>').'
											</p>
				';
			}
			if(isset($_SESSION['review']['response'])) {
				$modalReviewOpen = 1;
				$dataReview = $_SESSION['review']['response']['data'];
				$errorReview = $_SESSION['review']['response']['error'];
				unset($_SESSION['review']['response']);
				print_r($dataReview);
			}
			$view .= '
											<div class="modal fade" id="add_review'.$post['id'].'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
												<div class="modal-dialog">
													<form class="modal-content m-0" method="post">
			';
			$captcha_q = [rand(1,5), rand(1,5), rand(1,5)]; shuffle($captcha_q);
			$captcha_a = [array_sum($captcha_q), array_sum($captcha_q)+rand(1,6), array_sum($captcha_q)-rand(1,6)]; shuffle($captcha_a);
			$view .= '
														<input type="hidden" name="review[add][captcha_q]" value="'.implode('.', $captcha_q).'">
														<input type="hidden" name="review[add][item_id]" value="'.$post['id'].'">
														<div class="modal-header p-2">
															<p class="m-0 modal-title"><b>Новый отзыв</b> к анкете #'.$post['id'].'</p>
															<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
														</div>
														<div class="modal-body p-2">
															<div class="form-floating">
																<select class="form-select form-select-sm" id="rating" name="review[add][rating]">
			';
			$rating = [ 5 => 'Отлично (5 из 5)', 4 => 'Хорошо (4 из 5)', 3 => 'Нормально (3 из 5)', 2 => 'Плохо (2 из 5)', 1 => 'Ужасно (1 из 5)' ];
			foreach($rating as $k => $v) {
				if(isset($dataReview['rating']) && $dataReview['rating'] == $k) {
					$selected = 1;
				}
				$view .= '
																	<option value="'.$k.'"'.(isset($selected) ? ' selected' : '').'><span>'.$v.'</span></option>
				';
				unset($selected);
			}
			$view .= '
																</select>
																<label for="rating">Ваша оценка</label>
															</div>
															<div class="row g-1 mt-1 mb-2">
																<div class="col-6">
																	<input type="radio" class="btn-check" name="review[add][verify]" value="1" id="verify1" autocomplete="off" '.(!isset($dataReview['verify']) || (isset($dataReview['verify']) && $dataReview['verify'] == 1) ? ' checked' : '').'>
																	<label class="btn btn-outline-success btn-sm w-100" for="verify1"><i class="fa-solid fa-thumbs-up"></i> Информация в анкете ВЕРНАЯ</label>
																</div>
																<div class="col-6">
																	<input type="radio" class="btn-check" name="review[add][verify]" value="0" id="verify0" autocomplete="off"'.(isset($dataReview['verify']) && $dataReview['verify'] == 0 ? ' checked' : '').'>
																	<label class="btn btn-outline-danger btn-sm w-100" for="verify0"><i class="fa-solid fa-thumbs-down"></i> Информация в анкете ЛОЖНАЯ</label>
																</div>
															</div>
															'.(isset($errorReview['review']) ? '<div class="alert alert-danger p-2 mb-2 w-100"><p class="m-0 text-center">Текст отзыва не может быть пустым.</p></div>' : '').'
															<div class="form-floating">
																<textarea class="form-control'.(isset($errorReview['review']) ? ' is-invalid' : '').'" id="review" name="review[add][review]" '.(!isset($dataReview['review']) || $dataReview['review'] == '' ? 'placeholder="Напишите отзыв"' : '').' style="height:100px;resize:none">'.(isset($dataReview['review']) && $dataReview['review'] != '' ? $dataReview['review'] : '').'</textarea>
																<label for="review">Текст отзыва</label>
															</div>
														</div>
														<div class="modal-footer p-2">
														'.(isset($errorReview['captcha']) ? '<div class="alert alert-danger p-2 w-100"><p class="m-0 text-center">Не прошли антиспам проверку. Попробуйте еще раз.</p></div>' : '').'
															<div class="input-group input-group-sm w-100">
																<span class="input-group-text'.(isset($errorReview['captcha']) ? ' text-danger' : '').'">Не спам? Реши:&nbsp;<b>'.implode('&nbsp;<i class="fa-solid fa-plus text-muted" style="font-size:.5em"></i>&nbsp;', $captcha_q).'&nbsp;=&nbsp;</b></span>
			';
			foreach($captcha_a as $k => $v) {
			$view .= '
																<input type="radio" class="btn-check" name="review[add][captcha_a]" value="'.$v.'" id="var'.$k.'" autocomplete="off">
																<label class="btn btn-outline-'.(isset($errorReview['captcha']) ? 'danger' : 'secondary').' col" for="var'.$k.'">'.$v.'</label>
			';
			}
			$view .= '
															</div>
															<button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Закрыть</button>
															<button type="submit" class="btn btn-success btn-sm">Отправить</button>
														</div>
													</form>
												</div>
											</div>
			';
			if(isset($modalReviewOpen)) {
				$view .= '
											<script>
												$(document).ready(function() {
													$(\'#add_review'.$post['id'].'\').modal(\'show\');
												});
											</script>
				';
			}
			$view .= '
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer p-1">
							<div class="row g-1">
								<div class="col-12 col-md-3 d-flex align-items-center justify-content-center">
									<small class="text-muted">Регистрация: '.format_date($post['date_add']).'</small>
								</div>
								<div class="col-12 col-md-3 d-flex align-items-center justify-content-center">
								
								</div>
								<div class="col-12 col-md-3 d-flex align-items-center justify-content-center">
									<small class="text-muted">Статистика: '.$post['view_day'].' в день / '.$post['view_month'].' за месяц</small>
								</div>
								<div class="col-12 col-md-3 d-flex align-items-center justify-content-center">
		';
		if(isset($_COOKIE['abuse']['item']) && in_array($post['id'], $_COOKIE['abuse']['item'])) {
			$view .= '
									<button class="btn btn-sm btn-outline-danger w-100" disabled><i class="fa-solid fa-poo"></i> Вы уже пожаловались на эту анкету</button>
			';
		} else {
			$view .= '
									<div class="dropup-center dropup w-100">
										<button class="btn btn-sm btn-outline-danger w-100 dropdown-toggle" type="button" id="abuse_'.$post['id'].'" data-bs-toggle="dropdown" data-bs-auto-close="false"><i class="fa-solid fa-poo"></i> Пожаловаться</button>
										<div class="dropdown-menu w-100" aria-labelledby="abuse_'.$post['id'].'">
											<form method="post" class="mx-2 my-0">
												<input type="hidden" name="item[abuse][id]" value="'.$post['id'].'">
												<div class="alert alert-danger m-0 p-1 text-center">
													<small class="m-0 font2"><strong>Ваш номер телефона</strong> <br>необходим для оперативной связи с менеджером сайта. <br>Никому другому указанный номер не будет известен.</small>
												</div>
												<div class="form-floating my-1">
													<input type="tel" pattern="[0-9]{10}" class="form-control" id="abusePhone" name="item[abuse][phone]" placeholder="Введите значение" required>
													<label for="abusePhone"><span class="text-danger">*</span> Ваш номер телефона (формат: 9279174870)</label>
												</div>
												<button type="submit" name="item[abuse][reason]" value="Мошенничество" class="btn btn-sm btn-outline-danger w-100">Мошенничество</button>
												<button type="submit" name="item[abuse][reason]" value="Телефон не доступен" class="btn btn-sm btn-outline-danger w-100 my-1">Телефон не доступен</button>
												<button type="submit" name="item[abuse][reason]" value="Мои фото или данные в анкете" class="btn btn-sm btn-outline-danger w-100">Мои фото или данные в анкете</button>
											</form>
										</div>
									</div>
			';
		}
		$view .= '
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		';
		return $view;
	}

	function viewUser($user) {
		if(is_array($user)) {

			global $city, $types;
			
			$items = item_all($user['id']); krsort($items);

			// суммарный расход по анкетам
			$sum_items = 0;
			if(!empty($items)) {
				foreach($items as $i) {
					if($i['status_active'] == 1) {
						$sum_items += $i['sum'];
					}
				}
			}

			// проверка средств на балансе для действия
			function check_price($balance, $price, $text = false) {
				if($balance >= $price) {
					if($text) {
						return $text;
					} else {
						return true;
					}
				} else {
					if($text) {
						return '<p class="m-0 text-center text-danger"><b>Недостаточно денежных средств</b> <br><br>Для выполнения этого действия <br> необходимо минимум '.$price.' рублей</p>';
					} else {
						return false;
					}
				}
			}

			// список events и проверка на сегодняшние события
			$events = (new EventHelper($user['id']))->getAll(20);
			$count_today_events = 0;
			foreach($events as $today_event) {
				if(date('Ymd', strtotime($today_event['created_at'])) === getDateTime(null, 'Ymd')) {
					$count_today_events++;
				}
			}

			$view = '
				<div class="row justify-content-md-center g-0 user">
					<div class="col-12 col-lg-10">
						<div class="p-1 inner">
			';

			if(empty($items)) {
				$view .= '
						<div class="alert alert-info text-center m-0 p-2">
							<h2>Правила размещения анкет</h2>
							<p class="text-center">
								<strong>ЗАПРЕЩЕНО:</strong>
								<ol class="text-start">
									<li><strong>Размещать анкеты лицами младше 18-ти лет</strong> - мама с папой не обрадуются если найдут твои фото и номер здесь.</li>
									<li><strong>Размещать чужой номер телефона</strong> - неважно с какой целью (шутки ради, или месть бывшим). 
										Зачем, если такие анкеты блокируются после первого же обращения к админу?</li>
									<li><strong>Размещать анкету преследуя мошеннические цели</strong> - сбор предоплаты и прочий обман уже давно не работают в сфере 
										интим досуга, опять же жалоба от клиента и моментальная блокировка анкеты.</li>
									<li><strong>Размещать фотографии детского порно и зоофилии</strong> - пардон, но в этом случае 
										будет необходимость слить Ваши учётные данные правоохранительным органам.</li>
								</ol>
							</p>
							<p class="text-center">
								<strong>РЕКОМЕНДУЕТСЯ:</strong>
								<ol class="text-start">
									<li><strong>Размещать достоверные данные и актуальный номер телефона</strong> для связи по вопросам услуг с клиентами.</li>
									<li><strong>Размещать по возможности именно СВОИ фото</strong> (ну на край максимально типажные и очень похожие), 
										и желательно половые органы крупным планом всё же не выкладывать ибо необходима же какая 
										то И - интрига для клиентов =)</li>
									<li><strong>Трезво и адекватно оценивать Ваши внешние данные со списком услуг и с ценой</strong> за эти услуги. 
										Не стоит переоценивать свои возможности если хотите получать всё же звонки от заинтересованных клиентов.</li>
								</ol>
							</p>
							<p>Правила прочитали - теперь самое время добавить первую анкету =)</p>
							<div class="d-grid gap-2 d-md-flex justify-content-md-end">
								<a href class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#user_'.$user['id'].'_add_item"><i class="fa-regular fa-plus"></i> Добавить анкету</a>
								<a href="/placement/" target="_blank" class="btn btn-info text-white"><i class="fa-regular fa-hand-point-up"></i> О размещении анкет</a>
							</div>
							
						</div>
				';
			} else {
				global $types, $city, $site, $price_ank;

				$now = new DateTimeImmutable('now');

				foreach($items as $v) {

					// скидка поднятия анкет для PREMIUM анкет
					$topPrice = $v['status_premium'] == 1 ? $price_ank['top']/2 : $price_ank['top'];

					// отзывы анкеты
					$reviews = review_render_all($v['id']);
					
					$view .= '
						<div class="card mb-2">
							<div class="card-header position-relative px-1 py-2 text-center">
								<div class="position-absolute top-50 end-0 translate-middle-y" style="right:4px !important">
					';
					if($v['status_active'] == 1) {
						$view .= '
									<span class="badge rounded-pill text-bg-success"><i class="fa-solid fa-circle"></i> активна</span>
						';
						if($v['status_vip'] == 1) {
							$view .= '
									<span class="badge rounded-pill text-bg-success"><i class="fa-solid fa-star"></i> vip</span>
							';
							if($v['status_premium'] == 1) {
								$view .= '
									<span class="badge rounded-pill text-bg-success"><i class="fa-solid fa-crown"></i> premium</span>
								';
							}
						}
						if($v['status_real'] == 1) {
							$view .= '
									<span class="badge rounded-pill text-bg-success"><i class="fa-solid fa-camera"></i> реальное фото</span>
							';
						}
					} else {
						$view .= '
									<span class="badge rounded-pill text-bg-secondary"><i class="fa-regular fa-circle"></i> неактивна</span>
						';
					}
					$view .= '
								</div>
								<div class="row g-1">
									<div class="col-12 d-flex align-items-center">
										<small class="text-muted"><b>#'.$v['id'].'</b></small>
									</div>
								</div>
							</div>
							<div class="card-body p-1">
								<div class="row g-1">
									<div class="col-12 col-md-4">
										<div class="row g-1">
											<div class="col-4 d-flex justify-content-center align-items-center">
					';
					if(isset(explode(',', $v['photo'])[0])) {
						$view .= '
												<a href="/item/photo/'.$v['id'].'/" class="position-relative">
													<img src="'.(new ThumbHelper(explode(',', $v['photo'])[0], $v['id']))->generate(['width' => 72, 'height' => 72]).'" class="rounded">
													<span class="position-absolute top-0 start-0 badge bg-danger">
														<b>'.count(explode(',', $v['photo'])).'</b>
													</span>
													'.($v['status_real'] == 1 ? '<span class="position-absolute start-50 translate-middle-x badge bg-warning" style="bottom:3px">реальное</span>' : '').'
												</a>
						';
					}
					$view .= '
											</div>
											<div class="col-8 d-flex justify-content-center align-items-center text-center">
												<small>'.$types[$v['type']]['names'][0].' <br>
												<b>'.json_decode($v['info'], true)['name'].'</b><br>
												Телефон: <b>'.$v['phone'].'</b><br>
												Расход: <b>'.$v['sum'].'</b> руб. в день</small>
											</div>
										</div>
									</div>
									<div class="col-12 col-md-5 d-flex align-items-center">
										<div class="card w-100">
											<div class="card-header p-1 text-center">
												<small class="text-muted">Статусы</small>
											</div>
											<div class="card-body p-1">
												<div class="row justify-content-md-center g-1">
					';
					if($v['status_active'] == 1) {
						$lastTop = new DateTimeImmutable($v['date_top']);
						$diffTop = ($now->diff($lastTop)->days * 24 * 60) + 
							($now->diff($lastTop)->h * 60) + 
							$now->diff($lastTop)->i;
						$view .= '
													<div class="col-6 col-md-'.($v['status_vip'] == 1 ? 3 : 4).'">
														<button type="button" class="btn btn-'.($diffTop < 60 ? 'outline-danger' : 'secondary').' w-100 position-relative" data-bs-toggle="modal" data-bs-target="#status_top'.$v['id'].'">
															<i class="fa-solid fa-chevron-up"></i> <span class="d-inline d-lg-none d-md-none">Поднять</span>
															'.($diffTop < 60 ? '<i class="position-absolute top-50 end-0 translate-middle-y fa-solid fa-clock text-danger" style="right:5px !important"></i>' : '').'
														</button>
													</div>
													<div class="modal fade" id="status_top'.$v['id'].'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
														<div class="modal-dialog">
															<form method="post" class="modal-content m-0" id="request'.$v['id'].'">
																<input type="hidden" name="item[status][id]" value="'.$v['id'].'">
																<input type="hidden" name="item[status][user_id]" value="'.$user['id'].'">
																<div class="modal-header">
																	<p class="modal-title">Поднять анкету #'.$v['id'].'?</p>
																	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																</div>
																<div class="modal-body">
						';
						if($diffTop < 60) {
							$remainTime = 60-$diffTop;
							$view .= '
																	<div class="alert alert-warning text-center p-2 m-0">
																		<b>Поднятие этой анкеты было выполнено менее часа назад.</b> <br />
																		Повторно поднять анкету будет возможно через '.$remainTime.' '.format_num($remainTime, ['минута', 'минуты', 'минут']).'
						  											</div>
							';
						} else {
							$view .= '
																	'.check_price($user['balance'], $topPrice, '
																	'.($v['date_top'] != $v['date_add'] ? '<p class="text-center text-muted">Последний раз поднималась: <br>'.format_date($v['date_top']).'</p>' : '').'
																	<p class="text-center">С баланса спишется <br>стоимость поднятия: <b>'.$topPrice.' рублей</b></p>
																	<p class="m-0 text-center">Поднятие <b class="text-uppercase">автоматически</b> отправляет <br>анкету в рассылку телеграм канала: <br> <a href="https://t.me/elitedosug64" class="btn btn-outline-success mt-2" target="_blank"><i class="fa-brands fa-telegram"></i> Элит Досуг '.$city['value'][0].'</a></p>
																	').'
							';
						}
						$view .= '
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-outline-'.($diffTop > 60 ? 'secondary' : 'danger').'" data-bs-dismiss="modal">Закрыть</button>
																	'.($diffTop > 60 ? '<button type="submit" name="item[status][action]" value="top" id="top'.$v['id'].'" class="btn btn-success"'.(!check_price($user['balance'], $topPrice) ? ' disabled' : '').'>Поднять</button>' : '').'
																</div>
															</form>
															<div class="alert alert-success m-0 p-2 d-none" id="response'.$v['id'].'">
																<p class="text-center mb-1"><b>Поднятие и отправка анкеты в рассылку</b><br><small>Пожалуйста, ждите. По завершении процесса страница сама обновится.</small></p>
																<div class="progress" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
																	<div class="progress-bar progress-bar-striped progress-bar-animated bg-success font2" style="width: 100%">
																		Выполняется...
																	</div>
																</div>
															</div>
															<script>
																$(\'#top'.$v['id'].'\').on(\'click\', function () {
																	$(\'#request'.$v['id'].'\').addClass(\'d-none\');
																	$(\'#response'.$v['id'].'\').removeClass(\'d-none\');
																});
															</script>
														</div>
													</div>
													<div class="col-6 col-md-'.($v['status_vip'] == 1 ? 3 : 4).'">
														<button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#status_active'.$v['id'].'">
															<i class="fa-solid fa-toggle-on"></i> <span class="d-inline d-lg-none d-md-none">Скрыть</span>
														</button>
													</div>
													<div class="col-'.($v['status_vip'] == 1 ? 6 : 12).' col-md-'.($v['status_vip'] == 1 ? 3 : 4).'">
														<button type="button" class="btn btn-'.($v['status_vip'] != 1 ? 'outline-' : '').'success w-100" data-bs-toggle="modal" data-bs-target="#status_vip'.$v['id'].'">
															<i class="fa-solid fa-star"></i> <span class="d-inline d-lg-none d-md-none">VIP</span>
														</button>
													</div>
													<div class="modal fade" id="status_vip'.$v['id'].'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
														<div class="modal-dialog">
															<form method="post" class="modal-content m-0">
																<input type="hidden" name="item[status][id]" value="'.$v['id'].'">
																<input type="hidden" name="item[status][user_id]" value="'.$user['id'].'">
																<div class="modal-header">
																	<p class="modal-title">VIP статус анкеты #'.$v['id'].'</p>
																	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																</div>
																<div class="modal-body">
						';
						if($v['status_vip'] == 1) {
							$view .= '
																	<p class="m-0 text-center">Убрать анкету с VIP размещения? <br><br><b>Уверены?</b>'.($v['status_premium'] == 1 ? ' <br><br><i class="text-danger"><b>Важно:</b> При снятии статуса VIP - PREMIUM статус сбрасывается автоматически!</i>' : '').'</p>
																	
							';
						} else {
							$view .= check_price($user['balance'], $price_ank['vip'], '
																	<p class="m-0 text-center">Стоимость VIP: <b>'.$price_ank['vip'].' рублей в день</b> 
																	<br><br>С баланса спишется: <b>'.$price_ank['vip'].' рублей</b></p>
							');
						}
						$view .= '
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Закрыть</button>
																	<button type="submit" name="item[status][action]" value="vip" class="btn btn-success"'.(!check_price($user['balance'], $price_ank['vip']) && $v['status_vip'] == 0 ? ' disabled' : '').'>'.($v['status_vip'] == 1 ? 'Убрать' : 'Сделать').' VIP</button>
																</div>
															</form>
														</div>
													</div>
						';
						if($v['status_vip'] == 1) {
							$view .= '
													<div class="col-6 col-md-3">
														<button type="button" class="btn btn-'.($v['status_premium'] != 1 ? 'outline-' : '').'success w-100" data-bs-toggle="modal" data-bs-target="#status_premium'.$v['id'].'">
															<i class="fa-solid fa-crown"></i> <span class="d-inline d-lg-none d-md-none">PREMIUM</span>
														</button>
													</div>
													<div class="modal fade" id="status_premium'.$v['id'].'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
														<div class="modal-dialog">
															<form method="post" class="modal-content m-0">
																<input type="hidden" name="item[status][id]" value="'.$v['id'].'">
																<input type="hidden" name="item[status][user_id]" value="'.$user['id'].'">
																<div class="modal-header">
																	<p class="modal-title">PREMIUM статус анкеты #'.$v['id'].'</p>
																	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																</div>
																<div class="modal-body">
							';
							if($v['status_premium'] == 1) {
								$view .= '
																	<p class="m-0 text-center">Убрать анкету с PREMIUM размещения? <br><br><b>Уверены?</b></p>							
								';
							} else {
								$view .= check_price($user['balance'], $price_ank['premium'], '
																	<p class="m-0 text-center">Стоимость PREMIUM: <b>'.$price_ank['premium'].' рублей в день</b> 
																	<br><br>С баланса спишется: <b>'.$price_ank['premium'].' рублей</b></p>
								');
							}
							$view .= '
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Закрыть</button>
																	<button type="submit" name="item[status][action]" value="premium" class="btn btn-success"'.(!check_price($user['balance'], $price_ank['premium']) && $v['status_premium'] == 0 ? ' disabled' : '').'>'.($v['status_premium'] == 1 ? 'Убрать' : 'Сделать').' PREMIUM</button>
																</div>
															</form>
														</div>
													</div>
							';
						}
					} else {
						$view .= '
													<div class="col-12">
														<button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#status_active'.$v['id'].'">
															<i class="fa-solid fa-toggle-off"></i> Опубликовать
														</button>
													</div>
						';
					}
					$view .= '
													<div class="modal fade" id="status_active'.$v['id'].'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
														<div class="modal-dialog">
															<form method="post" class="modal-content m-0">
																<input type="hidden" name="item[status][id]" value="'.$v['id'].'">
																<input type="hidden" name="item[status][user_id]" value="'.$user['id'].'">
																<div class="modal-header">
																	<p class="modal-title">'.($v['status_active'] == 1 ? 'Скрыть анкету' : 'Опубликовать анкету').' #'.$v['id'].'?</p>
																	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																</div>
																<div class="modal-body">
					';
					if($v['status_active'] == 1) {
						$view .= '
																	<p class="m-0 text-center"><b>Уверены?</b></p>									
						';
					} else {
						$view .= check_price($user['balance'], $price_ank['blank'], '
																	<p class="m-0 text-center">Стоимость размещения обычной анкеты: <b>'.$price_ank['blank'].' рублей в день</b> 
																	<br><br>С вашего баланса спишется: <b>'.$price_ank['blank'].' рублей</b></p>
						');
					}
					$view .= '
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Закрыть</button>
																	<button type="submit" name="item[status][action]" value="active" class="btn btn-success"'.(!check_price($user['balance'], $price_ank['blank']) && $v['status_active'] == 0 ? ' disabled' : '').'>'.($v['status_active'] == 1 ? 'Скрыть' : 'Опубликовать').'</button>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12 col-md-3 d-flex align-items-center">
										<div class="card w-100">
											<div class="card-header p-1 text-center">
												<small class="text-muted">Действия</small>
											</div>
											<div class="card-body p-1">
												<div class="row g-1">
					';
					if($reviews != false) {
						$view .= '
													<div class="col-12 col-md-3">
														<button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#reviews'.$v['id'].'">
															<span class="badge rounded-pill bg-danger">'.count($reviews).'</span>
															<i class="fa-solid fa-comments"></i>
															<span class="d-inline d-lg-none d-md-none"> Отзывы</span>
														</button>
													</div>
													<div class="modal fade" id="reviews'.$v['id'].'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
														<div class="modal-dialog modal-dialog-centered">
															<div class="modal-content">
																<div class="modal-header">
																	<p class="modal-title">Управление отзывами анкеты #'.$v['id'].'</p>
																	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																</div>
																<div class="modal-body py-1 px-2">
						';
						foreach($reviews as $review) {
							$rating = $review['rating'] == 1 ? 'danger' : ($review['rating'] == 2 || $review['rating'] == 3 ? 'warning' : 'success');
							$view .= '
																	<div class="alert alert-'.$rating.' my-1 p-2 w-100">
																		<form method="post">
																			<input type="hidden" name="review[reply][id]" value="'.$review['id'].'">
																			<div class="mb-1">
																				<p class="m-0"><strong class="text-uppercase">Отзыв:</strong> '.$review['text']['client'].' <i class="fa-regular fa-thumbs-'.($review['verify'] == 1 ? 'up' : 'down').'"></i></p>
																				'.(isset($review['text']['answer']) ? '<div  class="collapse show" id="reply'.$review['id'].'"><hr class="text-'.$rating.' my-1" /><p class="m-0"><strong class="text-uppercase">Ваш ответ:</strong> '.$review['text']['answer'].'</p></div>' : '').'
																			</div>
																			<div class="collapse mb-1" id="reply'.$review['id'].'">
																				<div class="form-floating">
																					<textarea id="label_reply'.$review['id'].'" class="form-control" name="review[reply][reply]" placeholder="Напишите ответ на отзыв" style="height:80px;resize:none">'.($review['text']['answer'] ?? '').'</textarea>
																					<label for="label_reply'.$review['id'].'">Ответ на отзыв</label>
																				</div>
																			</div>
																			<div class="row g-1">
																				<div class="col-4 d-flex align-items-center">
																					<small class="m-0 w-100 text-center">
							';
							for($i = 1; $i <= 5; $i++) {
								if($i <= $review['rating']) {
									$view .= '
																						<i class="fa-solid fa-star"></i>
									';
								} else {
									$view .= '
																						<i class="fa-regular fa-star"></i>
									';
								}
							}
							$view .= '
																					</small>
																				</div>
																				<div class="col-6 d-flex align-items-center">
																					<div class="collapse show fade w-100" id="reply'.$review['id'].'">
																						<a href="#reply'.$review['id'].'" class="btn btn-'.$rating.' btn-sm w-100" data-bs-toggle="collapse"><i class="fa-solid fa-comments"></i> '.(isset($review['text']['answer']) ? 'Изменить ответ' : 'Ответить на отзыв').'</a>
																					</div>
																					<div class="collapse fade w-100" id="reply'.$review['id'].'">
																						<div class="row g-1">
																							<div class="col-3">
																								<a href="#reply'.$review['id'].'" class="btn btn-outline-'.$rating.' btn-sm w-100" data-bs-toggle="collapse"><i class="fa-solid fa-chevron-left"></i></a>
																							</div>
																							<div class="col-9">
																								<button type="submit" class="btn btn-'.$rating.' btn-sm w-100">'.(isset($review['text']['answer']) ? 'Обновить' : 'Сохранить').'</a>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div class="col-2 d-flex align-items-center">
																					<a href="#delete'.$review['id'].'" type="button" class="btn btn-outline-'.$rating.' btn-sm w-100" data-bs-toggle="collapse"><i class="fa-solid fa-ban"></i></a>
																				</div>
																			</div>
																		</form>

																		<div class="card text-bg-danger mt-1 p-2 collapse" id="delete'.$review['id'].'">
																			<div class="card-body m-0 p-0">
							';
							if($user['balance'] >= $price_ank['delete_review'] || $v['status_premium'] == 1) {
								$view .= '
																				<p class="mb-2 text-center"><strong>Удаление отзыва</strong></p>
																				<p class="m-0 text-center">'.($v['status_premium'] != 1 ? 'С баланса спишется стоимость удаления отзыва: '.$price_ank['delete_review'].' рублей.' : 'Эта анкета имеет статус &laquo;Premium&raquo; поэтому это бесплатно.').'</p>
																				<form method="post" class="d-flex justify-content-center mt-2">
																					<input type="hidden" name="review[remove][item_id]" value="'.$v['id'].'">
																					<input type="hidden" name="review[remove][user_id]" value="'.$v['user_id'].'">
																					<button type="submit" name="review[remove][id]" value="'.$review['id'].'" class="btn btn-light text-danger btn-sm me-1">Удалить</button>
																					<a href="#delete'.$review['id'].'" type="button" class="btn btn-light text-muted btn-sm" data-bs-toggle="collapse">Отмена</a>
																				</form>
								';
							} else {
								$view .= '
																				<p class="m-0 text-center">Недостаточно денег на балансе для удаления отзыва, <br>необходимо пополнить баланс на '.$price_ank['delete_review'].' рублей.</p>
								';
							}
							$view .= '
																			</div>
																		</div>
																	</div>
							';
						}
						$view .= '
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Закрыть</button>
																</div>
															</div>
														</div>
													</div>
						';
					}
					$view .= '
													<div class="col-12 col-md-'.($reviews != false ? 3 : 4).'">
														<a href="/item/edit/'.$v['id'].'/" class="btn btn-outline-secondary w-100">
															<i class="fa-solid fa-pen"></i>
															<span class="d-inline d-lg-none d-md-none"> Изменить</span>
														</a>
													</div>
													<div class="col-12 col-md-'.($reviews != false ? 3 : 4).'">
														<a href="/'.$v['type'].'/'.$v['id'].'/" class="btn btn-outline-secondary w-100">
															<i class="fa-solid fa-file"></i>
															<span class="d-inline d-lg-none d-md-none"> Просмотр</span>
														</a>
													</div>
													<div class="col-12 col-md-'.($reviews != false ? 3 : 4).'">
														<button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#remove'.$v['id'].'">
															<i class="fa-solid fa-close"></i>
															<span class="d-inline d-lg-none d-md-none"> Удалить</span>
														</a>
													</div>
													<div class="modal fade" id="remove'.$v['id'].'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
														<div class="modal-dialog">
															<form method="post" class="modal-content m-0">
																<input type="hidden" name="item[delete][id]" value="'.$v['id'].'">
																<input type="hidden" name="item[delete][user_id]" value="'.$user['id'].'">
																<div class="modal-header">
																	<p class="modal-title">Удалить анкету #'.$v['id'].'?</p>
																	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																</div>
																<div class="modal-body">
																	<p class="m-0 text-danger text-center">Данное действие необратимо</p>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Закрыть</button>
																	<button type="submit" class="btn btn-danger">Удалить</button>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>							
									</div>
								</div>
							</div>
							<div class="card-footer text-center p-1">
								<div class="row g-1">
									<div class="col-12 col-md-4">
										<small class="text-muted">Регистрация: '.format_date($v['date_add']).'</small>
									</div>
									<div class="col-12 col-md-4">
										<small class="text-muted">Поднятие: '.($v['date_add'] == $v['date_top'] ? 'не было' : format_date($v['date_top'])).'</small>
									</div>
									<div class="col-12 col-md-4">
										<small class="text-muted">Статистика: '.$v['view_day'].' день / '.$v['view_month'].' месяц</small>
									</div>
								</div>
							</div>
						</div>
					';
				}
			}
			$view .= '
						</div>
					</div>
					<div class="umenu" style="z-index:1">
						<ul>
							<li>
								<span data-bs-toggle="modal" data-bs-target="#user_'.$user['id'].'_edit"><i class="fa-solid fa-address-card"></i> '.$user['id'].'</span>
							</li><li>
								<span data-bs-toggle="modal" data-bs-target="#user_'.$user['id'].'_balance"><i class="fa-solid fa-coins"></i>&nbsp;&nbsp;<b>'.$user['balance'].' руб</b></span>
							</li><li>
								<span data-bs-toggle="modal" data-bs-target="#user_'.$user['id'].'_add_item"><i class="fa-solid fa-file-circle-plus"></i></span>
							</li><li>
								<span data-bs-toggle="modal" data-bs-target="#user_'.$user['id'].'_events"><i class="fa-solid fa-address-book"></i>'.($count_today_events > 0 ? ' +'.$count_today_events : '').'</span>
							</li><li>
								<span data-bs-toggle="modal" data-bs-target="#user_'.$user['id'].'_exit"><i class="fa-solid fa-person-walking-arrow-right"></i></span>
							</li>
						</ul>
					</div>

					<div class="modal fade" data-bs-backdrop="static" id="user_'.$user['id'].'_balance">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<form method="post">
								<div class="modal-header justify-content-center p-1">
									<p class="modal-title text-center">Управление балансом</p>
								</div>
								<div class="modal-body p-2">
									<p class="m-0 text-center">Баланс: <b>'.$user['balance'].' рублей</b></p>
		';
		if(isset($sum_items) && $sum_items > 0) {
			if(floor($user['balance']/$sum_items) > 0) {
				$view .= '
									<p class="m-0 text-center">Этого хватит на <b>'.floor($user['balance']/$sum_items).'</b> '.format_num(floor($user['balance']/$sum_items), ['день', 'дня', 'дней']).'</p>					
				';
			} else {
				$view .= '
									<p class="m-0 text-center text-danger">Недостаточный баланс для оплаты услуг размещения</p>					
				';
			}
			$view .= '
									<p class="m-0 text-center text-muted">Расход: <b>'.$sum_items.' рублей</b> в день</p>					
			';
		}
		$view .= '
									<div class="alert alert-success text-center mt-2 mb-0 p-2">
										Пополнение баланса ВРЕМЕННО возможно <br />
										только в ручном режиме, <br />
										для пополнения баланса свяжитесь с менеджером<br />
										<a href="tel:'.format_phone($city['manager']['phone']).'" class="btn btn-success mt-1">'.format_phone($city['manager']['phone']).'</a>
										<a href="tg://resolve?domain=elited_admin" class="btn btn-success mt-1"><i class="fa-solid fa-paper-plane"></i></a>
										<a href="https://wa.me/+7'.$city['manager']['phone'].'" class="btn btn-success mt-1"><i class="fa-brands fa-whatsapp"></i></a>
									</div>
									<!--<div class="form-floating mt-2">
										<input type="number" min="1000" max="200000" step="500" class="form-control" name="payment[create_invoice][amount]" value="'.((count($items) > 0 ? count($items)*1000 : 1000)).'" required>
										<label for="userLoginLogin">Сумма пополнения</label>
									</div>-->
								</div>
								<div class="modal-footer p-1">
									<!--<button class="btn btn-sm btn-primary" type="submit" name="payment[create_invoice][user_id]" value="'.$user['id'].'">Оплатить</button>-->
									<button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Закрыть</button>
								</div>
								</form>
							</div>
						</div>
					</div>

					<div class="modal fade" data-bs-backdrop="static" id="user_'.$user['id'].'_add_item">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header justify-content-center p-1">
									<p class="modal-title text-center">Добавить новую анкету</p>
								</div>
								<div class="modal-body p-2">
			';
			foreach($types as $type => $value) {
				$view .= '
									<a href="/item/add/?type='.$type.'" class="btn btn-outline-secondary w-100 my-1">Раздел &laquo;'.$value['names'][0].'&raquo;</a>
				';
			}
			$view .= '
								</div>
								<div class="modal-footer p-1">
									<button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Закрыть</button>
								</div>
							</div>
						</div>
					</div>

					<div class="modal fade" data-bs-backdrop="static" id="user_'.$user['id'].'_events">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header justify-content-center p-1">
									<p class="modal-title text-center">Лента действий</p>
								</div>
								<div class="modal-body p-2 font2">
									'.($count_today_events > 0 ? '<div class="alert alert-success m-0 p-1"><p class="m-0 text-center">Сегодня было произведено '.$count_today_events.' '.format_num($count_today_events, ['действие', 'действия', 'действий']).'</p></div>' : '').'
			';
			foreach($events as $key_event => $event) {
				$view .= '
									<p class="mb-1">
										<small class="'.(date('Ymd', strtotime($event['created_at'])) === getDateTime(null, 'Ymd') ? 'text-success' : 'text-muted').'">'.format_date($event['created_at']).'</small><br>
										<span>'.$event['event'].'</span>
									</p>
									'.($key_event !== array_key_last($events) ? '<hr class="text-muted m-0" />' : '').'
				';
			}
			$view .= '
								</div>
								<div class="modal-footer p-1">
									<!--<a href="/user/events/" class="btn btn-sm btn-primary">Все действия</a>-->
									<button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Закрыть</button>
								</div>
							</div>
						</div>
					</div>

					<div class="modal fade" data-bs-backdrop="static" id="user_'.$user['id'].'_exit">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-body p-4">
									<p class="m-0 text-danger text-center"><b>Уверены</b>, что хотите выйти?</p>
								</div>
								<div class="modal-footer p-1">
									<a href="/user/logout/" class="btn btn-sm btn-danger">Да, выйти</a>
									<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Закрыть</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
			return $view;
		} else {
			return false;
		}
	}

	function viewManager($user) {

		global $site, $city, $types, $price_ank, $response;

		$demoAccounts = [2]; //demo accounts in system

		$allUsers = [];
		$allSumBalance = 0;
		$allItemsSummary = 0;
		foreach(user_all(
			null, 'reg', [
				'balance' => 'DESC', 
				'reg_date' => 'DESC'
			]) as $key => $regUser) {
			if (
				$regUser['reg_date'] == $regUser['login_date'] && 
				$regUser['balance'] == 0 && 
				(new DateTimeImmutable('now'))->diff(new DateTimeImmutable($regUser['reg_date']))->format('%a') >= 100
			) {
				$checkEmptyUser[$regUser['id']] = true;
				$emptyUsers[$key] = $regUser;
			} else {
				if ($regUser['balance'] > 0 && !in_array($regUser['id'], $demoAccounts)) { //exclude demo accounts balance
					$allSumBalance += $regUser['balance'];
				}
	
				$allUsers[$key] = $regUser;
	
				$allUsers[$key]['items'] = item_all($regUser['id']);
	
				$allUsers[$key]['summary'] = 0;
				if (!empty($allUsers[$key]['items'])) {
					foreach ($allUsers[$key]['items'] as $item) {
						if ($item['sum'] == 0) {
							continue;
						}
						$allUsers[$key]['summary'] += $item['sum'];
					}
				}
				if (!in_array($regUser['id'], $demoAccounts)) { //exclude demo accounts balance
					$allItemsSummary += $allUsers[$key]['summary'];
				}
			}
		}
		if (!empty($emptyUsers)) {
			$allUsers = array_merge($allUsers, $emptyUsers);
		}

		$view = '';

		if (isset($response)) {
			$key = key($response);
			$code = $response[$key]['code'];
			$response = $response[$key]['data'];
			$fail = [
				404 => [
					'title' => 'Не найдено',
					'text' => 'Запись не найдена'
				],
				400 => [
					'title' => 'Нет параметров',
					'text' => 'Неверные некоторые параметры'
				]
			];

			if ($code == 200) {
				if ($key == 'added_balance') {
					$sms = 'Элит Досуг '.$city['value'][0].': Пополнение на '.$response['addedSum'].' рублей. Ваш баланс: '.$response['userBalance'].' рублей.';
					$event = 'Менеджер пополнил на '.$response['addedSum'].' рублей. Текущий баланс: '.$response['userBalance'].' рублей.';
					$message = 'Пополнение баланса на '.$response['addedSum'].' рублей.<br />Смс отправлено.';
				}
				if ($key == 'change_status') {
					$termMessage = [
						'active' => $response['valueAction'] == 1 ? 'опубликована' : 'скрыта',
						'premium' => 'статус «PREMIUM» '.($response['valueAction'] == 1 ? 'назначен' : 'снят'),
						'vip' => 'статус «VIP» '.($response['valueAction'] == 1 ? 'назначен' : 'снят'),
						'real' => 'статус «Реальное фото» '.($response['valueAction'] == 1 ? 'назначен' : 'снят'),
						'top' => 'была поднята',
					];
					//send top to channels
					if ($response['actionItem'] == 'top') {
						global $channel;
						if (isset($city['social']['telegamChannelId'])) {
							(new NotifyHelper())->sendItemToTelegramChannel($response['itemId']);
						}
					}
					$sms = 'Элит Досуг '.$city['value'][0].': Анкета ID: '.$response['itemId'].' '.$termMessage[$response['actionItem']].' менеджером.'.(isset($response['sumItem']) ? ' Расход: '.$response['sumItem'].' рублей в день.' : '').(isset($response['userOutBalance']) ? ' Списано с баланса: '.$response['userOutBalance'].' рублей.' : '');
					$event = 'Анкета ID '.$response['itemId'].' '.$termMessage[$response['actionItem']].' менеджером.'.(isset($response['userOutBalance']) ? ' С баланса списано: '.$response['userOutBalance'].' рублей.' : '');
					$message = 'Анкета ID: '.$response['itemId'].' '.$termMessage[$response['actionItem']].'. '.(isset($response['sumItem']) ? '<br />Расход: '.$response['sumItem'].' рублей в день.' : '').(isset($response['userOutBalance']) ? '<br />Списано с баланса: '.$response['userOutBalance'].' рублей.' : '').'<br />Смс отправлено.';
				}
			}
			
			if (isset($event)) {
				(new EventHelper($response['userId']))->add($event);
			}
			if (isset($sms)) {
				(new NotifyHelper())->sendSms(
					$sms,
					json_decode(file_get_contents('https://rest.elited.ru/user/'.$response['userId'].'/getPhone'))->userPhone
				);
			}
			$view .= '
				<div class="toast-container position-fixed bottom-0 start-50 translate-middle-x p-3">
					<div id="response" class="toast bg-'.($code == 200?'primary' : 'danger').'-subtle" role="alert" data-bs-delay="10000" aria-live="assertive" aria-atomic="true">
						<div class="toast-header bg-'.($code==200 ? 'primary' : 'danger').' text-white">
							<strong class="me-auto">'.($code==200 ? 'ID: '.$response['userId'] : $fail[$code]['title']).'</strong>
							<small>только что</small>
							<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
						</div>
						<div class="toast-body">'.($code==200 ? $message : $fail[$code]['text']).'</div>
					</div>
				</div>
				<script>
					bootstrap.Toast.getOrCreateInstance(document.getElementById(\'response\')).show();
				</script>
			';
		}

		$view .= '
			<div class="row justify-content-center g-0 font2 manager">
				<div class="col-11 col-lg-10">
					<div class="mb-1 inner">
						<div class="row">
							<div class="col-12 col-md-6 d-flex align-items-center">
								<div>
									<span class="text-secondary">Общий баланс зарегистрированных в системе:</span> '.$allSumBalance.' рублей<br />
									<span class="text-secondary">Общий по анкетам расход в день:</span> '.$allItemsSummary.' рублей<br />
									'.(isset($checkEmptyUser) ? '<span class="text-secondary">Пустых пользователей:</span> '.count($checkEmptyUser).'<br />' : '').'
									<small class="text-danger">Из статистики исключены демо аккаунты ID '.implode(', ', $demoAccounts).'</small>
								</div>
							</div>
							<div class="col-12 col-md-6 d-flex align-items-center">
								<a href="/user/logout/" class="btn btn-danger">Выход <i class="fa-solid fa-person-walking-arrow-right"></i></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-11 col-lg-10">
					<div class="mb-1 inner">
		';
		foreach($allUsers as $u) {
			$class = 'success';
			if ($u['balance'] == 0) {
				$class = 'dark';
			}
			if ($u['reg_date'] == $u['login_date']) {
				$class = 'danger';
			}
			if ($u['balance'] > 0 && $u['summary'] > 0 && $u['summary'] > $u['balance']) {
				$class = 'warning';
			}
			if (in_array($u['id'], [2,31,45])) {
				$class = 'primary';
			}

			$view .= '
						<div class="card mb-2 p-0 border-'.$class.'-subtle">
							<div class="card-header p-1 bg-'.$class.'-subtle">
								<div class="row g-1">
									<div class="col-8 col-md-9 d-flex align-items-center">
										<div class="w-100 text-'.$class.' text-sm">
											<a href="#" class="text-'.$class.'"><strong>ID: '.$u['id'].'</strong> • '.$u['login'].'</a> • <i class="fa-solid fa-mobile-screen-button"></i> +7'.$u['phone'].'
										</div>
									</div>
									<div class="col-4 col-md-3 d-flex align-items-center">
										<div class="w-100 text-right">
											<div class="row g-1">
												<div class="col-6">
													<button type="button" class="w-100 btn btn-outline-'.$class.' btn-sm">
														<i class="fa-solid fa-lock"></i><span class="d-none d-sm-inline"> Блокировка
													</button>
												</div>
												<div class="col-6">
													<button type="button" class="w-100 btn btn-outline-'.$class.' btn-sm">
														<i class="fa-solid fa-trash"></i><span class="d-none d-sm-inline"> Удалить
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body p-1">
								<div class="row g-1">
									<div class="col-12 col-md-6 d-flex align-items-center">
										<div class="w-100">
			';
			if (empty($u['items'])) {
				$view .= '
											<button type="button" class="btn btn-outline-secondary btn-sm my-1" disabled>
												<i class="fa-solid fa-ban"></i> Анкет нет
											</button>
				';
			} else {
				$view .= '
											<button type="button" class="btn btn-outline-'.$class.' btn-sm my-1" data-bs-toggle="collapse" data-bs-target="#items'.$u['id'].'" aria-expanded="false" aria-controls="items'.$u['id'].'">
												'.count($u['items']).' '.format_num(count($u['items']), ['анкета', 'анкеты', 'анкет']).' <i class="fa-solid fa-chevron-down"></i>
											</button>
				';
			}
			$view .= '
											<button type="button" class="btn btn-outline-'.$class.' btn-sm my-1" data-bs-toggle="collapse" data-bs-target="#activity'.$u['id'].'" aria-expanded="false" aria-controls="activity'.$u['id'].'">
												<i class="fa-solid fa-address-book" aria-hidden="true"></i>
											</button>
										</div>
									</div>
									<div class="col-12 col-md-6 d-flex align-items-center">
										<form method="post" class="m-0 w-100" id="balance'.$u['id'].'">
											<input type="hidden" name="manager[added_balance][userId]" value="'.$u['id'].'">
											<div class="input-group input-group-sm collapse show" id="addedBalance'.$u['id'].'">
												'.(!isMobile() ? '<span class="input-group-text">Баланс:</span>' : '').'
												<input type="text" class="form-control'.(isset($response['userId'])&&$response['userId']==$u['id']?' text-primary':'').'" value="'.($u['balance'] > 0 ? $u['balance'].'₽' : 'пустой').'" readonly>
												'.(isset($u['summary'])&&$u['summary'] > 0 ? (!isMobile() ? '<span class="input-group-text">Расход:</span>' : '').'<input type="text" class="form-control" value="'.$u['summary'].'₽ на '.round($u['balance']/$u['summary']).' '.format_num(round($u['balance']/$u['summary']), ['день', 'дня', 'дней']).'" readonly>' : '').'
												<button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#addedBalance'.$u['id'].'" aria-expanded="false" aria-controls="addedBalance'.$u['id'].'">Пополнить</button>
											</div>
											<div class="input-group input-group-sm collapse" id="addedBalance'.$u['id'].'">
												<span class="input-group-text">Пополнить на:</span>
												<input type="number" class="form-control" name="manager[added_balance][addedSum]" value="1000" min="100" step="100" required>
												<button id="send" class="btn btn-secondary" type="submit"><i class="fa-solid fa-chevron-right"></i></button>
											</div>
										</form>
									</div>
								</div>
								<div class="collapse" id="activity'.$u['id'].'">
									<div class="card mt-1">
										<div class="card-header p-1 text-center">Лента действий пользователя ID: '.$u['id'].'</div>
										<div class="card-body p-1">
											!!!
										</div>
									</div>
								</div>
			';
			if (!empty($u['items'])) {
				$show = false;
				if (isset($response['userId'])) {
					if ($response['userId'] == $u['id'] && isset($key) && $key == 'change_status') {
						$show = true;
					}
				}
				$view .= '
								<div class="collapse'.($show ? ' show' : '').'" id="items'.$u['id'].'">
									<div class="card mt-1">
										<div class="card-header p-1 text-center">Управление анкетами пользователя ID: '.$u['id'].'</div>
										<div class="card-body p-1">
				';
				krsort($u['items']);
				foreach ($u['items'] as $item) {
					$checkActivate = $u['balance'] > $price_ank['blank'];
					$checkTop = $u['balance'] > ($item['status_premium'] == 1 ? $price_ank['top']/2 : $price_ank['top']);
					$view .= '
											<div class="card my-2">
												<div class="card-body w-100 p-2">
													<div class="row g-1">
														<div class="col-12 col-lg-4 d-flex align-items-center">
															<div class="row g-1 w-100">
																<div class="col-12 col-md-6">
																	<strong>#'.$item['id'].'</strong>&nbsp;
																	<span class="text-secondary">&laquo;'.$types[$item['type']]['names'][0].' '.json_decode($item['info'])->name.'&raquo;</span>
																</div>
																<div class="col-12 col-md-6">
																	'.($item['sum'] > 0 ? 'Размещение: '.$item['sum'].' рублей в день' : '').'
																</div>
															</div>
														</div>
														<div class="col-12 col-lg-5 d-flex">
															<form method="post" class="w-100">
					';
					if($checkActivate || $item['status_active'] == 1) {
						$view .= '
																<input type="hidden" name="manager[change_status_for_item][item_id]" value="'.$item['id'].'">
																<input type="hidden" name="manager[change_status_for_item][price]" value="'.base64_encode(serialize($price_ank)).'">
						';
					}
					$view .= '
																
					';
					if ($item['status_active'] == 0) {
						$view .= '
															<button type="submit" class="btn btn-outline-danger btn-sm w-100" '.(!$checkActivate ? 'disabled': 'name="manager[change_status_for_item][active]" value="'.!$item['status_active'].'"').'>
																<i class="fa-solid fa-toggle-off"></i> '.($checkActivate ? 'Активировать анкету' : 'Активация невозможна').'
															</button>
						';
					} else {
						$view .= '
															<div class="row g-1 w-100">
																<div class="col-12 col-md-3">
																	<button type="submit" class="btn btn-danger btn-sm w-100" name="manager[change_status_for_item][active]" value="'.!$item['status_active'].'">
																		<i class="fa-solid fa-toggle-on"></i> Скрыть
																	</button>
																</div>
																<div class="col-12 col-md-3">
																	<button type="submit" class="btn btn-primary btn-sm w-100" name="manager[change_status_for_item][top]" value="1"'.(!$checkTop ? ' disabled' : '').'>
																		<i class="fa-solid fa-chevron-up"></i> Поднять
																	</button>
																</div>
						';
						if ($item['status_premium'] == 1) {
							$view .= '
																<div class="col-12 col-md-4">
																	<button type="submit" class="btn btn-primary btn-sm w-100" name="manager[change_status_for_item][premium]" value="'.!$item['status_premium'].'">
																		<i class="fa-solid fa-toggle-on"></i> Premium
																	</button>
																</div>
							';
						} else {
							$view .= '
																<div class="col-12 col-md-2">
																	<button type="submit" class="btn btn-outline-primary btn-sm w-100" '.($item['status_vip'] == 0 ? 'disabled' : 'name="manager[change_status_for_item][premium]" value="'.!$item['status_premium'].'"').'>
																		<i class="fa-solid fa-toggle-off"></i> Premium
																	</button>
																</div>
																<div class="col-12 col-md-2">
																	<button type="submit" class="btn btn-'.($item['status_vip'] == 0 ? 'outline-' : '').'primary btn-sm w-100" name="manager[change_status_for_item][vip]" value="'.!$item['status_vip'].'">
																		<i class="fa-solid fa-toggle-'.($item['status_vip'] == 0 ? 'off' : 'on').'"></i> VIP
																	</button>
																</div>
							';
						}
						$view .= '
																<div class="col-12 col-md-2">
																	<button type="submit" class="btn btn-'.($item['status_real'] == 0 ? 'outline-' : '').'primary btn-sm w-100" name="manager[change_status_for_item][real]" value="'.!$item['status_real'].'">
																		<i class="fa-solid fa-toggle-'.($item['status_real'] == 0 ? 'off' : 'on').'"></i> Реал
																	</button>
																</div>
															</div>
						';
					}
					$view .= '
															</form>
														</div>
														<div class="col-12 col-lg-3 d-flex">
															<div class="row g-1 w-100">
																<div class="col-5">
																	<a href="/item/edit/'.$item['id'].'/?user_id='.$u['id'].'" class="btn btn-outline-secondary btn-sm w-100">
																		<i class="fa-solid fa-pencil"></i> Данные
																	</a>
																</div>
																<div class="col-5">
																	<a href="/item/photo/'.$item['id'].'/?user_id='.$u['id'].'" class="btn btn-outline-secondary btn-sm w-100">
																		<i class="fa-solid fa-image"></i> Фото
																	</a>
																</div>
																<div class="col-2">
																	<button type="button" class="btn btn-outline-danger btn-sm w-100">
																		<i class="fa-solid fa-trash"></i>
																	</button>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="card-footer text-secondary">
													<div class="row g-1 w-100">
														<div class="col-4">
															<small><span class="text-uppercase text-body-tertiaryt">Регистрация:</span> '.format_date($item['date_add']).'</small>
														</div>
														<div class="col-4">
															<small><span class="text-uppercase text-body-tertiary">Обновление:</span> '.format_date($item['date_edit']).'</small>
														</div>
														<div class="col-4">
															<small><span class="text-uppercase text-body-tertiary">Поднятие:</span> '.format_date($item['date_top']).'</small>
														</div>
													</div>
												</div>
											</div>
					';
				}
				$view .= '
										</div>
									</div>
								</div>
				';
			}
			$view .= '
							</div>
							<div class="card-footer p-1 bg-'.$class.'-subtle text-'.$class.'">
								<small>Регистрация: '.format_date($u['reg_date']).' '.(isset($checkEmptyUser[$u['id']]) ? 'Пустой аккаунт' : ($u['reg_date'] != $u['login_date'] && $u['login_date'] != '0000-00-00 00:00:00' ? ' • Вход: '.format_date($u['login_date']) : '')).'</small>
							</div>
						</div>
			';
		}
		$view .= '
					</div>
				</div>
			</div>
		';
		return $view;
	}

	function viewWidgetTelegramFooter($data) {
		$view = '
			<div class="widget telegram text-center">
				<div class="icon">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAAXNSR0IB2cksfwAAAAlwSFlzAAALEwAACxMBAJqcGAAABDdJREFUeJyt1ltMU3ccB/AzwLopIGCGLgadE2aRm8D2sCVeHkx4MNMHedQlbjPRLPHJvS5BmBuCzDAsuDkHbI6VAlIuk42hIrKhVC4KKHIthQqF3ksLtuW7b7nJ2uJ1/+STc87//H7f32lJcxAELyu+yuaTUD0Vkvj79IeJVx6nUM17NfZ+miIHGege96Ws+ZS10bSafd7iPNf2ykn/+CrrDjaksVFLeAYn9bH+s+2V1nD2+y0bHltufi1Wbg6Mq7AcZGEdGyyEF6CKq5jMi5VbdjFH5HVIzGXjmphy0wEO6yEn4SXY6GrMZZM4uszg858B20r0/lFlhoPUG11mxCuyM0cWVapPYK7v7ACxdNwnUqbdESnT1W0r0TkJ/wMjM7Npi7h4QhC2SsdDOCiNFxbCi9gmm8Ceah2O3TQhudbgfr+P2clbf9OIhHeLxj6gCcLzer9Ug2M3DKgctGHaOQPXkg/YEPHrqHutNKJoNFTYckmdQngWcdEj7JZrIOmwYMjswHz24ip8YPbs+0WtoVjhnZ+HazYXquCN+NIwkipGcaJRB2nPJAZNdtiZrjQ+RsuYFVqrY3HI6Ra91ww6LLydr+zflK/Egs0FSsQVqZAkf4QvGrWoHrRi3OaEg09utTtxS21FXrsWBZ0GqMz2xSEnGsaxNGcB8/OEsIv9U4SNFJ4/gKTyEXzZpMWVwUk8nv9OZnjQ2uyoHTThSO0IJO16qC0OLP3GDtWo4crxolTYcKHXQdj4Yy8+kqugGLXBwideCHAd64fMOF43gn3lQ5B2mzjQgaXLyafYLVPCleNFqfDW+W49YcP33Ygr7MXxv4ZxoW0c15VG3NNMIrdFg72lA9hbpsTFDgP0U064/c2550Ase105XkiFdZKue+ty78NlfW4XNp2/j8TCh9gj7cG+kj7EF3QjJv8hTjVpoHP7BAur3zCNzT88mM+Yy1ok6UoX3szpkIae64Q77s9yncf89ABnm8cw7XD/DHOrcdiM9ZIOjwxy0H5hbXb7J+QkrM2+6+bJXpjkLj6W9+KRedpjyHeKMS+9s9ooQgg+2xZFvYQnWt2Oc9by+pC8Bwq1afb3srA+/2PQS33rDOXwPEgIympZHZR158iaM3dUBJfAeXPnCqxZcu4SIWnFkaoeFHdq8GefDuG89ujNutNAO4O+bfEVAs40C4GZzeEBmc3nAzJu2wjPIzDjFiLOKRApUXjez2xW0VHmBi++T/wzbvv5n761i66uTm+yE16BiTmpzAzzeDOuSm8SrfrmH/EbX/8te/1Uo5HwElTsT2VOCId5f8+zyIcSVn51M1uU1tBHeE4zK9MaGth3lP1h9PT/Vljsy6YtotQbyStS64tXnKzXEJbhoHZR2o0c1u9kb/DT05csv5P1LiK/k9dDKc4v5dph35RruVQ6T0rp3N/P+xF8mCDyXS7vX8Aq0rJwm7AzAAAAAElFTkSuQmCC" />
				</div>
				<div class="row g-1">
					<div class="col-3">
						<div class="avatar" style="background-image: url(\''.$data['avatar'].'\')"></div>
					</div>
					<div class="col-9">
						<h5 class="title">'.$data['title'].'</h5>
						<div class="row meta">
							'.($data['username'] ? '<div class="col-6 text-left"><small class="username">@'.$data['username'].'</small></div>' : '').'
							<div class="col-6 text-right"><small class="members">'.$data['members'].' '.format_num($data['members'], ['подписчик', 'подписчика', 'подписчиков']).'</small></div>
						</div>
						<a href="'.$data['invite'].'" target="_blank" class="w-100 button">Подписаться на канал</a>
					</div>
				</div>
			</div>
		';
		return $view;
	}