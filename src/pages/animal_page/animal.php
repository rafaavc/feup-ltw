<!DOCTYPE html>
<html>

<head>
	<title>To The Rescue!</title>
	<link rel="stylesheet" href="../../myProjects/webProject/icofont/css/icofont.min.css">
	<link rel="stylesheet" type="text/css" href="../../style.css" />
	<link rel="stylesheet" type="text/css" href="animal.css" />
	<script src="../../scripts.js" defer></script>
</head>

<body>

	<?php
	include_once(dirname(__FILE__) . '/../../templates/common/header.php'); ?>
	<section class='petProfile'>
		<img src='../../images/cuteDoggos.jpeg'>
		<div>
			<header>
				<h3>Dogs name, age</h3>
				<h4>White Golden Retriver, 100cm </h4>
			</header>
			<p>Et has minim elitr intellegat. Mea aeterno eleifend antiopam ad, nam no suscipit quaerendum. At nam minimum ponderum. Est audiam animal molestiae te.</p>
			<footer>
				<input type="button" id="favorite" value="Add to Favorites" />
				<input type="button" id="adopt" value="Adopt it" />
			</footer>
		</div>
	</section>

	<div id="petPhotos"></div>

	<?php
	include_once(dirname(__FILE__) . '/../../templates/common/footer.php');

	?>
</body>

</html>