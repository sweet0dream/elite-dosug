<?php
    global $site, $city, $types;
    $logo_img = '/assets/images/logo/';
	if (file_exists($site['path'] . '/assets/images/logo/' . $city['domain'] . '.webp')) {
		$logo_img .= $city['domain'];
	} else {
		$logo_img .= 'default';
	}
	$logo_img .= '.webp';

	$logo_alt = 'Проститутки '. $city['value'][1] . ' Элит Досуг';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container">
		<a class="navbar-brand m-0 logo" href="/">
			<img src="<?= $logo_img ?>" alt="<?= $logo_alt ?>" style="width:75%">
		</a>
		<button class="navbar-toggler mobile" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-expanded="false">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse text-end" id="menu">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0 menu">
				<?php foreach($types as $k => $v) : ?>
				<li class="nav-item">
					<a class="nav-link<?= (isset($route[1]) && $route[1] == $k ? ' active' : '') ?> text-center" title="<?= $types[$k]['names'][2]?>" href="/<?= $k ?>/"><?= $v['names'][1] ?></a>
				</li>
				<?php endforeach ?>
				<li class="nav-item"><a href="#" target="_blank" class="nav-link text-info text-center"><i class="fa-brands fa-telegram"></i> Telegram</a></li>
			</ul>
			<?php if(isset($_SESSION['auth']['type'])) : ?>
			<a href="<?= $site['url'].'/user/' ?>" class="btn btn-light"><?= ($_SESSION['auth']['type'] == 'man' ? 'Управление' : 'Личный кабинет') ?></a>
			<?php else : ?>
			<button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#placement">Размещение</button>
			<?php endif ?>
		</div>
	</div>
</nav>

