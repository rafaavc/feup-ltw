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
		<div id="petProfileImage"> </div>
		<div id="petInfo">
			<header>
				<h3>Dogs name, age</h3>
				<h4>White Golden Retriver, 100cm, Location </h4>
			</header>
			<p>Et has minim elitr intellegat. Mea aeterno eleifend antiopam ad, nam no suscipit quaerendum. At nam minimum ponderum. Est audiam animal molestiae te.</p>
			<footer>
				<input type="button" id="favorite" value="Add to Favorites" />
				<input type="button" id="adopt" value="Adopt it" />
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
			<input type="submit"/>
		</form>
	</section>


	<?php
	include_once(dirname(__FILE__) . '/../../templates/common/footer.php');

	?>
</body>

</html>