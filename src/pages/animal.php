<?php
$GLOBALS['section'] = 'discover';
include_once(dirname(__FILE__) . '/../control/db.php');
include_once(dirname(__FILE__) . '/../templates/common/header.php');
?>
<script src="<?= getRootURL() ?>/javascript/slider.js"></script>
<script src="<?= getRootURL() ?>/javascript/animal.js" defer></script>
<?php
require_once(dirname(__FILE__) . "/../control/api/pet.php");


$pet = API\getPet($GLOBALS['id']);
?>
<section class='petProfile' data-id="<?= $pet['id'] ?>">
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
					echo $pet['color'] . ' ' . $pet['race'] . ', ' . $pet['location'];
				} else if ($pet['specie'] != null) {
					echo $pet['color'] . ' ' . $pet['specie'] . ', ' . $pet['location'];
				}
				?></h4>
		</header>
		<p><?= $pet['description'] ?></p>
		<?php if (Session\isAuthenticated()) { ?>
			<footer>
				<input type="button" id="favorite" class="simpleButton" value="Add to Favorites" />
				<input type="button" id="adopt" class="simpleButton contrastButton" value="Adopt it" />
			</footer>
		<?php } ?>
	</div>
</section>
<div id="mySlider" class="ss-parent">

	<?php
	$photos = API\getPetPhotos($pet['id']);
	for ($i = 0; $i < count($photos); $i++) {
	?>
		<div class="ss-child">
			<div style="background: url(<?= getRootURL() ?>/images/petPictures/<?= $photos[$i]['photoId'] ?>.jpg); background-size: cover; background-position: 50%"> </div>

		</div>
	<?php
	}
	?>
	<div class="ss-nav"></div>
</div>
<script>
	const slider = new SimpleSlider("mySlider", 3000, "40rem");
	slider.start();
</script>

<section id="comments">
	<h4>Comments:</h4>
	<?php
	$posts = API\getPosts($pet['id']);
	for ($i = 0; $i < count($posts); $i++) {
		$user = API\getUserById($posts[$i]['userId']); ?>
		<article class="comment">
			<img src='../../images/userProfilePictures/<?= $user['id'] ?>.jpg' />
			<p><?= $posts[$i]['description'] ?></p>
			<span class="user"><?= $user['shortName'] ?></span>
			<span class="date"><?= $posts[$i]['postDate'] ?></span>
		</article>

	<?php
	}
	if (Session\isAuthenticated()) {
		$user = Session\getAuthenticatedUser();
	?>
		<form>
			<h4>Add Comment:</h4>
			<textarea name="text"></textarea>
			<input type="submit" class="contrastButton" />
		</form>
	<?php } else {
	?>
		<h4>To add a comment:</h4>
		<ul>
			<li><a href="<?= getRootUrl() ?>/signin" class="simpleButton">Sign In</a></li>
			<li><a href="<?= getRootUrl() ?>/signup" class="simpleButton contrastButton">Sign Up</a></li>
		</ul>
	<?php } ?>
</section>


<?php
include_once(dirname(__FILE__) . '/../templates/common/footer.php');

?>
</body>

</html>