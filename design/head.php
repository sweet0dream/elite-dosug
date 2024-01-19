<?php
	/*if(isset($_COOKIE['auth'])) {
		print_r($_COOKIE['auth']);
	}*/
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

	<link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
	
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
	<? include 'menu.php'; ?>
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