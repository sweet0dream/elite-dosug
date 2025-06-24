<?php
    global $city, $route;
    if(isset($_SESSION['response'])) {
		if(isset($_SESSION['response']['modal'])) {
			$modalOpen = 1; $modalTab = $_SESSION['response']['modal'];
		}
		if(isset($_SESSION['response']['value'])) {
			$value = $_SESSION['response']['value'];
		}
		if(isset($_SESSION['response']['errors'])) {
			$errors = $_SESSION['response']['errors'];
		}
		$response = $_SESSION['response'];
		unset($_SESSION['response']);
	}
?>
<?php
	if(isset($_SESSION['payment']['link'])) {
		$link = $_SESSION['payment']['link'];
		unset($_SESSION['payment']['link']);
		redirect($link);
	}
?>
<!DOCTYPE HTML>
<html lang="ru">
<head>
	<!--charset-->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= itMeta($route) ?>
	
	<link rel="icon" href="/assets/images/favicon/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="/assets/images/favicon/favicon.ico" type="image/x-icon">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/assets/images/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon/favicon-16x16.png">
                                              
	<!--touchicon-->
	<link rel="apple-touch-icon" sizes="57x57" href="/assets/touch-icons/<?= $city['name'] ?>/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/assets/touch-icons/<?= $city['name'] ?>/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/assets/touch-icons/<?= $city['name'] ?>/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/assets/touch-icons/<?= $city['name'] ?>/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/assets/touch-icons/<?= $city['name'] ?>/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/assets/touch-icons/<?= $city['name'] ?>/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/assets/touch-icons/<?= $city['name'] ?>/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/assets/touch-icons/<?= $city['name'] ?>/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/touch-icons/<?= $city['name'] ?>/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="/assets/touch-icons/<?= $city['name'] ?>/android-icon-192x192.png">
	<link rel="manifest" href="/assets/touch-icons/<?= $city['name'] ?>/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/assets/touch-icons/<?= $city['name'] ?>/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">                                                    
	
	<script src="/assets/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap.min.css">
	<script src="/assets/popper.min.js"></script>
	<script src="/assets/bootstrap.min.js"></script>
	<script src="/assets/fontawesome.js"></script>

	<link rel="stylesheet" type="text/css" href="/assets/custom.css">
	<script defer src="/assets/custom.js"></script>
</head>
<body>
	<?= !isset($_SESSION['auth']) ? renderAdv('b0') : '' ?>
	<?php include 'menu.php'; ?>
<?php
		if(!isset($_SESSION['auth'])) {
			include 'modal.php';
		}
?>
	<main>
<?php
	if(empty($route) || (isset($route[1]) && isset($types[$route[1]]))) {
		if(!isset($route[2])) {
			include 'vip.php';
		}
	}
?>