<!DOCTYPE html>
<html>

<head>
	<title>To The Rescue!</title>
	<link rel="stylesheet" href="../../myProjects/webProject/icofont/css/icofont.min.css">
	<link rel="stylesheet" type="text/css" href="<?= getRootURL() ?>/components/style.css" />
	<link rel="stylesheet" type="text/css" href="animal.css" />
	<script src="../../scripts.js" defer></script>
</head>

<body>

	<?php
	include_once(dirname(__FILE__) . '/../control/db.php');
	$pet = getPet($GLOBALS['id']);
	include_once(dirname(__FILE__) . '/../templates/common/header.php');
	?>
	<section class='petProfile'>
		<div id="petProfileImage" style="background-image: url(<?= '../images/PetProfilePictures/' . $pet['id'] . '.jpg' ?>);"> </div>
		<div id="petInfo">
			<header>
				<h3><?= $pet['name'] ?>,
					<?php
					$birthDate = explode("-", $pet['birthdate']);
					//get age from date or birthdate
					$years = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md")
						? ((date("Y") - $birthDate[0]) - 1)
						: (date("Y") - $birthDate[0]));
					if ($years > 1) {
						echo $years . ' years';
					} else if ($years == 1) {
						echo $years . ' year';
					}
					$months = (date("d", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("d")
						? ((date("m") - $birthDate[1]) - 1)
						: (date("m") - $birthDate[1]));
					if ($months > 0) {
						if ($years > 0) {
							echo ' and ' . $months . ' months';
						} else {
							echo $months . ' months';
						}
					}
					?>
				</h3>
				<h4>White Golden Retriver, 100cm, Location </h4>
			</header>
			<p>Et has minim elitr intellegat. Mea aeterno eleifend antiopam ad, nam no suscipit quaerendum. At nam minimum ponderum. Est audiam animal molestiae te.</p>
			<footer>
				<input type="button" id="favorite" value="Add to Favorites" />
				<input type="button" id="adopt" class="contrastButton" value="Adopt it" />
			</footer>
		</div>
	</section>

	<div id="petPhotos"></div>

	<section id="comments">
		<h4>Comments:</h4>
		<article class="comment">
			<img src='../../images/rafaProfilePic.jpg' />
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vestibulum mi a velit ...</p>
			<span class="user">rafaavc</span>
			<span class="date">15:32 04/12/2020</span>
		</article>
		<article class="comment">
			<img src='../../images/rafaProfilePic.jpg' />
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vestibulum mi a velit ...</p>
			<span class="user">rafaavc</span>
			<span class="date">15:32 04/12/2020</span>
		</article>
		<form action='#'>
			<h4>Add Comment:</h4>
			<textarea name="text"></textarea>
			<input type="submit" class="contrastButton" />
		</form>
	</section>


	<?php
	include_once(dirname(__FILE__) . '/../templates/common/footer.php');

	?>
</body>

</html>