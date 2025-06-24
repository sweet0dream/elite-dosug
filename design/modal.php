<?php
    global$city, $price_ank, $price_adv;
?>
<!-- modal -->
	<div class="modal fade" id="placement" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Размещение рекламы</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="nav justify-content-center nav-tabs" id="nav-tab" role="tablist">
						<button class="nav-link <?= (!isset($modalTab) || ($modalTab == 'login') ? 'active' : '') ?>" id="nav-login-tab" data-bs-toggle="tab" data-bs-target="#nav-login" type="button" role="tab" aria-controls="nav-login" aria-selected="true">Вход</button>
						<button class="nav-link <?= (isset($modalTab) && $modalTab == 'regin' ? 'active' : '') ?>" id="nav-regin-tab" data-bs-toggle="tab" data-bs-target="#nav-regin" type="button" role="tab" aria-controls="nav-regin" aria-selected="false">Регистрация</button>
					</div>
					<div class="tab-content" id="nav-tabContent">
						<div class="tab-pane fade <?= (!isset($modalTab) || ($modalTab == 'login') ? 'show active' : '') ?>" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab" tabindex="0">
							<form method="post" class="mt-2 mb-0">
								<?= isset($errors) && !is_array($errors) ? '<div class="alert alert-danger text-center mb-2" role="alert"><strong>Неизвестная ошибка</strong></div>' : '' ?>
								<div class="form-floating mb-2">
									<input type="text" class="form-control<?= isset($errors['login']['login']) ? ' is-invalid' : '' ?>" id="userLoginLogin" name="user[login][login]" <?= (isset($value['login']['login']) && $value['login']['login'] != '' ? 'value="'.$value['login']['login'].'"' : (isset($_COOKIE['auth']['login']) ? 'value="'.$_COOKIE['auth']['login'].'"' : 'placeholder="Введите значение"')) ?> required>
									<label for="userLoginLogin"><span class="text-danger">*</span> <?= isset($errors['login']['login']) ? '<span class="text-danger">Такого логина не существует</span>' : 'Ваш логин' ?></label>
								</div>
								<div class="form-floating mb-2">
									<input type="password" class="form-control<?= isset($errors['login']['password']) ? ' is-invalid' : '' ?>" id="userLoginPassword" name="user[login][password]" placeholder="Введите значение" required>
									<label for="userLoginPassword"><span class="text-danger">*</span> <?= isset($errors['login']['password']) ? '<span class="text-danger">Неправильный пароль</span>' : 'Ваш пароль' ?></label>
								</div>
                                <?php
                                if(isset($errors['login']['password']) && $errors['login']['password'] == 'incorrect') : ?>
								<div class="alert alert-danger text-center p-2 mb-2" role="alert">
									<p class="mb-2">Забыли пароль?</p>
									<a href="#" class="btn btn-outline-danger btn-sm w-100">Восстановить</a>
								</div>
                                <?php
                                endif ?>
								<button type="submit" class="btn btn-<?= isset($errors) ? 'danger' : 'primary' ?> w-100">Войти</button>
							</form>
						</div>
						<div class="tab-pane fade <?= (isset($modalTab) && $modalTab == 'regin' ? 'show active' : '') ?>" id="nav-regin" role="tabpanel" aria-labelledby="nav-regin-tab" tabindex="0">
							<form method="post" class="mt-2 mb-0">
                                <?php
                                if($city['user_start_balance']) : ?>
								<input type="hidden" name="user[regin][balance]" value="<?= $city['user_start_balance'] ?>">
								<div class="alert alert-success text-center mb-2 p-1">
									<p class="mb-1"><strong>Приятный бонус!</strong></p>
									<p class="m-0">При регистрации Вам автоматически зачисляется <br />бонус <?= $city['user_start_balance'] ?> рублей для оплаты размещения анкет.</p>
								</div>
                                <?php
                                endif ?>
								<?= isset($errors) && !is_array($errors) ? '<div class="alert alert-danger text-center mb-2" role="alert"><strong>Неизвестная ошибка</strong></div>' : '' ?>
								<div class="form-floating mb-2">
									<input type="text" class="form-control<?= isset($errors['regin']['login']) ? ' is-invalid' : '' ?>" id="userReginLogin" name="user[regin][login]" <?= (isset($value['regin']['login']) && $value['regin']['login'] != '' ? 'value="'.$value['regin']['login'].'"' : '') ?> required>
									<label for="userReginLogin"><span class="text-danger">*</span> <?= isset($errors['regin']['login']) ? '<span class="text-danger">Такой логин уже существует - используйте другой</span>' : 'Придумайте логин (только латинские буквы и цифры)' ?></label>
								</div>
                                <?php
                                if(isset($errors['regin']['login']) && $errors['regin']['login'] == 'dublicate') : ?>
								<div class="alert alert-danger text-center p-2 mb-2" role="alert">
									<p class="mb-2">Возможно указанный логин принадлежит Вам, попробуйте:</p>
									<a href="#" class="btn btn-outline-danger btn-sm w-100">Восстановить пароль</a>
								</div>
                                <?php
                                endif ?>
								<div class="form-floating mb-2">
									<input type="password" class="form-control" id="userReginPassword" name="user[regin][password]" required>
									<label for="userReginPassword"><span class="text-danger">*</span> Придумайте пароль (только латинские буквы и цифры)</label>
								</div>
								<div class="form-floating mb-2">
									<input type="tel" pattern="[0-9]{10}" class="form-control" id="userReginPhone" name="user[regin][phone]" <?= (isset($value['regin']['phone']) && $value['regin']['phone'] != '' ? 'value="'.$value['regin']['phone'].'"' : '') ?> required>
									<label for="userReginPhone"><span class="text-danger">*</span> Номер телефона (формат: 9279174870)</label>
								</div>
								<div class="form-floating mb-3">
									<input type="text" class="form-control" id="userReginCode" name="user[regin][code]" <?= (isset($value['regin']['code']) && $value['regin']['code'] != '' ? 'value="'.$value['regin']['code'].'"' : '') ?> required>
									<label for="userReginCode"><span class="text-danger">*</span> Секретное слово (одно слово на русском)</label>
								</div>
								<button type="submit" class="btn btn-<?= isset($errors) ? 'danger' : 'primary' ?> w-100">Зарегистрировать</button>
							</form>
						</div>
						<p class="text-muted text-end mt-2 mb-0"><span class="text-danger">*</span> - обязательные поля</p>
					</div>

					<div class="alert alert-info text-center mb-0 p-2" role="alert">
						<p class="mb-1">По всем вопросам <br>по размещению рекламы <br>обращаться к администратору:</p>
						<div class="row g-2">
							<div class="col">
								<a href="tg://resolve?domain=elited_admin" class="btn btn-info btn-lg w-100 text-white my-1"><i class="fa-solid fa-paper-plane"></i></a>
							</div>
							<div class="col">
								<a href="https://wa.me/+7927 ?>" class="btn btn-info btn-lg w-100 text-white my-1"><i class="fa-brands fa-whatsapp"></i></a>
							</div>
						</div>
						<a href="tel:+7927" class="btn btn-info w-100 text-white my-1"><i class="fa-solid fa-mobile-screen-button"></i> +7927</a>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary w-100" data-bs-toggle="collapse" data-bs-target="#info_placement">Стоимость размещения</button>
					<div class="collapse w-100" id="info_placement">
						<ul class="nav nav-pills nav-fill mb-1" id="pills-tab" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link active" id="pills-ank-tab" data-bs-toggle="pill" data-bs-target="#pills-ank" type="button" role="tab" aria-controls="pills-ank" aria-selected="true">Размещение анкет</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-adv-tab" data-bs-toggle="pill" data-bs-target="#pills-adv" type="button" role="tab" aria-controls="pills-adv" aria-selected="false">Размещение баннеров</button>
							</li>
						</ul>
						<div class="tab-content" id="pills-tabContent">
							<div class="tab-pane fade show active" id="pills-ank" role="tabpanel" aria-labelledby="pills-ank-tab" tabindex="0">
								<ul class="list-group">
									<li class="list-group-item">
										<div class="row g-2">
											<div class="col-6 d-flex align-items-center">
												<span class="text-muted w-100 text-center">Обычное <br>размещение</span>
											</div>
											<div class="col-6 d-flex align-items-center">
												<div class="w-100 text-center">
													<b><?= $price_ank['blank'] ?></b> руб. <span class="text-muted">в день</span> <hr class="m-1">
													<b><?= $price_ank['blank']*30 ?></b> руб. <span class="text-muted">в месяц</span>
												</div>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row g-2">
											<div class="col-6 d-flex align-items-center">
												<span class="text-muted w-100 text-center">&laquo;VIP&raquo; <br>размещение</span>
											</div>
											<div class="col-6 d-flex align-items-center">
												<div class="w-100 text-center">
													<b><?= $price_ank['blank']+$price_ank['vip'] ?></b> руб. <span class="text-muted">в день</span> <hr class="m-1">
													<b><?= ($price_ank['blank']+$price_ank['vip'])*30 ?></b> руб. <span class="text-muted">в месяц</span>
												</div>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row g-2">
											<div class="col-6 d-flex align-items-center">
												<span class="text-muted w-100 text-center">&laquo;PREMIUM&raquo; <br>размещение</span>
											</div>
											<div class="col-6 d-flex align-items-center">
												<div class="w-100 text-center">
													<b><?= $price_ank['blank']+$price_ank['vip']+$price_ank['premium'] ?></b> руб. <span class="text-muted">в день</span> <hr class="m-1">
													<b><?= ($price_ank['blank']+$price_ank['vip']+$price_ank['premium'])*30 ?></b> руб. <span class="text-muted">в месяц</span>
												</div>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row g-2">
											<div class="col-6 d-flex align-items-center">
												<span class="text-muted w-100 text-center">Поднятие <br>анкеты</span>
											</div>
											<div class="col-6  d-flex align-items-center">
												<span class="w-100 text-center">
													<b><?= $price_ank['top'] ?></b> руб. <br><span class="text-muted">единоразово</span>
												</span>
											</div>
										</div>
									</li>
								</ul>
							</div>
							<div class="tab-pane fade" id="pills-adv" role="tabpanel" aria-labelledby="pills-adv-tab" tabindex="0">
								<ul class="list-group">
									<li class="list-group-item">
										<div class="row g-2">
											<div class="col-6 d-flex align-items-center">
												<span class="text-muted w-100 text-center">Секция B0</span>
											</div>
											<div class="col-6 d-flex align-items-center">
												<span class="w-100 text-center">
													<b><?= $price_adv['b0'] ?></b> руб. <span class="text-muted">в месяц</span>
												</span>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row g-2">
											<div class="col-6 d-flex align-items-center">
												<span class="text-muted w-100 text-center">Секция B1</span>
											</div>
											<div class="col-6 d-flex align-items-center">
												<span class="w-100 text-center">
													<b><?= $price_adv['b1'] ?></b> руб. <span class="text-muted">в месяц</span>
												</span>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row g-2">
											<div class="col-6 d-flex align-items-center">
												<span class="text-muted w-100 text-center">Секция B2</span>
											</div>
											<div class="col-6 d-flex align-items-center">
												<span class="w-100 text-center">
													<b><?= $price_adv['b2'] ?></b> руб. <span class="text-muted">в месяц</span>
												</span>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row g-2">
											<div class="col-6 d-flex align-items-center">
												<span class="text-muted w-100 text-center">Секция B3</span>
											</div>
											<div class="col-6 d-flex align-items-center">
												<span class="w-100 text-center">
													<b><?= $price_adv['b3'] ?></b> руб. <span class="text-muted">в месяц</span>
												</span>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row g-2">
											<div class="col-6 d-flex align-items-center">
												<span class="text-muted w-100 text-center">Изготовление <br>статичного <br>баннера</span>
											</div>
											<div class="col-6 d-flex align-items-center">
												<span class="w-100 text-center">
													<b><?= $price_adv['create_static'] ?></b> руб. <br><span class="text-muted">единоразово</span>
												</span>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row g-2">
											<div class="col-6 d-flex align-items-center">
												<span class="text-muted w-100 text-center">Изготовление <br>анимированного <br>баннера</span>
											</div>
											<div class="col-6 d-flex align-items-center">
												<span class="w-100 text-center">
													<b><?= $price_adv['create_animate'] ?></b> руб. <br><span class="text-muted">единоразово</span>
												</span>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		<?php if(isset($modalOpen)) : ?>
            <script>
                $(document).ready(function() {
                    $('#placement').modal('show');
                });
            </script>
		<?php endif ?>
	<!--!modal-->