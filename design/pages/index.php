<div class="card mx-3 my-1">
	<div class="card-body p-2">
		<h1 class="card-title text-center fs-5">Лучшие индивидуалки и проститутки <?= $city['value'][1] ?> ждут Вас здесь</h1>
		<p class="card-text">
			Где еще можно так быстро и просто снять <b>шлюху в <?= $city['value'][1] ?></b> по абсолютно любым параметрам, да еще и с проверенными фото? 
			Абсолютно любой район города, ведь проституки обитают везде. Нет возможности пригласить девушку в гости? Ищи проституток с апартаментами, 
			или услугой минет в автомобиле - таких тут хватает. Обрати внимание - анкеты с реальными отзывами! Утехи в компании 
			<b>индивидуалки <?= $city['value'][1] ?></b> - обычное дело, но наш сайт позволит организовать 
			твой секс-досуг быстро и безопасно. Удели этому пару минут и ты найдешь девушку, которая полностью подойдет тебе. 
			Нужна недорогая шлюха? Их достаточно. Хочешь развлечься с толстой проституткой? И такие есть! 
			Главное, помни: не оставляй предоплат и предохраняйся. Хорошего поиска!</p>
	</div>
</div>
<div class="row g-0">
	<div class="col-12">
		<section class="partIntro">
			<div class="row justify-content-md-center g-0">
<?php
	$keyIndexItems = $city['domain'] . '-index';
	$cacheIndexItems = (new CacheHelper())->getData($keyIndexItems);
	if (!$cacheIndexItems) {
		$cacheIndexItems = (new CacheHelper())->setData(
			$keyIndexItems,
			(new DatabaseHelper('item'))->fetchAll([
				'city_id' => $city['id'],
				'status_active' => 1
			], [
				'date_top' => 'DESC'
			])->getResult()
		);
	}
	foreach($cacheIndexItems as $post) {
		echo '<div class="col-12 col-lg-4 col-md-6">'.viewIntro(item_decode($post)).'</div>';
	}
?>
			</div>
		</section>
    </div>
</div>