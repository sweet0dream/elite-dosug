<?php
    global $city;
    $param = [
        'city_id' => $city['id'],
        'status_active' => 1,
        'status_vip' => 1
    ];
    if(isset($route[1]) && isset($types[$route[1]])) {
        $param['type'] = $route[1];
    }
    $order = [
        'status_premium' => 'DESC',
        'date_top' => 'DESC'
    ];

	$cacheVipItems = new DatabaseHelper('item')->fetchAll($param, $order)->getResult();
?>
<div class="partVip">
	<?= !isset($_SESSION['auth']) ? renderAdv('b1') : ''?>
	<div class="row justify-content-md-center g-0">
		<?= isMobile() ? '<style>.partVip .item .info {bottom: -35px;}</style>' : ''?>
<?php 
	$count_vip = 0;
	foreach($cacheVipItems as $post) {
		echo '<div class="col-6 col-lg-2 col-md-4 col-sm-6">'.viewVip(item_decode($post)).'</div>';
		$count_vip++;
		if($count_vip == 4) {
			echo !isset($_SESSION['auth']) ? renderAdv('b2') : '';
		} elseif($count_vip == 8) {
			echo !isset($_SESSION['auth']) ? renderAdv('b3') : '';
		}
	}
	if($count_vip < 4) {
		echo !isset($_SESSION['auth']) ? renderAdv('b2').renderAdv('b3') : '';
	}
?>
	</div>
</div>