<?php
$GLOBALS['section'] = 'discover';
include_once(dirname(__FILE__) . '/../control/db.php');
$pet = getPet($GLOBALS['id']);
include_once(dirname(__FILE__) . '/../templates/common/header.php');
?>
<section class='petProfile'>
	<div id="petProfileImage" style="background-image: url(<?= '../images/petProfilePictures/' . $pet['id'] . '.jpg' ?>);"> </div>
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
			<h4><?php
				if ($pet['race'] != null) {
					echo getColor($pet['color'])['name'] . ' ' . getRace($pet['race'])['name'] . ', ' . $pet['location'];
				} else if ($pet['specie'] != null) {
					echo getColor($pet['color'])['name'] . ' ' . getSpecie($pet['specie'])['name'] . ', ' . $pet['location'];
				}
				?></h4>
		</header>
		<p><?= $pet['description'] ?></p>
		<footer>
			<input type="button" id="favorite" value="Add to Favorites" />
			<input type="button" id="adopt" class="contrastButton" value="Adopt it" />
		</footer>
	</div>
</section>

<?php
$photos = getPetPhotos($pet['id']);
for ($i = 0; $i < count($photos); $i++) {
	echo '<img class="petPhotos" src="../images/petPictures/' . $photos[$i]['photoId'] . '.jpg"></img>';
}
?>

<section id="comments">
	<h4>Comments:</h4>
	<?php
	$posts = getPosts($pet['id']);
	for ($i = 0; $i < count($posts); $i++) {
		$user = getUser($posts[$i]['userId']); ?>
		<article class="comment">
			<img src='../../images/userProfilePictures/<?= $user['id'] ?>.jpg' />
			<p><?= $posts[$i]['description'] ?></p>
			<span class="user"><?= $user['name'] ?></span>
			<span class="date"><?= $posts[$i]['postDate'] ?></span>
		</article>

	<?php
	}
	?>
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