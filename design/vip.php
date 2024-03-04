<?php
	$itemsVips = db_connect()->where('city_id', $city['id'])->where('status_active', 1)->where('status_vip', 1)->orderBy('status_premium', 'DESC')->orderBy('date_top', 'DESC');
	if(isset($route[1]) && isset($types[$route[1]])) {
		$itemsVips->where('type', $route[1]);
	}
	$posts = $itemsVips->get('item');
?>
<div class="partVip">
	<?= $city['id'] == 1 && !isset($_SESSION['auth']) ? renderAdv('b1') : ''?>
	<div class="row justify-content-md-center g-0">
		<?= isMobile() ? '<style>.partVip .item .info {bottom: -35px;}</style>' : ''?>
<?php 
	$count_vip = 0;
	foreach($posts as $post) {
		echo '<div class="col-6 col-lg-2 col-md-4 col-sm-6">'.viewVip(item_decode($post)).'</div>';
		$count_vip++;
		if($count_vip == 4) {
			echo $city['id'] == 1 && !isset($_SESSION['auth']) ? renderAdv('b2') : '';
		} elseif($count_vip == 8) {
			echo $city['id'] == 1 && !isset($_SESSION['auth']) ? renderAdv('b3') : '';
		}
	}
	if($count_vip < 4) {
		echo $city['id'] == 1 && !isset($_SESSION['auth']) ? renderAdv('b2').renderAdv('b3') : '';
	}
?>
	</div>
</div>