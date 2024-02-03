<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container">
		<a class="navbar-brand m-0 logo" href="/">
			<img src="/assets/images/logo-6400.webp" alt="Проститутки Саратова Элит Досуг" style="width:75%">
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
			</ul>
			<?php if(isset($_SESSION['auth']) && isset($_SESSION['auth']['type'])) : ?>
			<a href="<?= $site['url'].'/user/' ?>" class="btn btn-light"><?= ($_SESSION['auth']['type'] == 'adm' ? 'Админпанель' : 'Личный кабинет') ?></a>
			<?php else : ?>
			<button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#placement">Размещение</button>
			<?php endif ?>
		</div>
	</div>
</nav>

