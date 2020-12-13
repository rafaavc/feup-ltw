<?php

$GLOBALS['section'] = 'discover';
include_once(dirname(__FILE__) . '/../control/db.php');
require_once(dirname(__FILE__) . "/../control/api/pet.php");

$pet = API\getPet($GLOBALS['id']);
if ($pet == false) {
	include_once(dirname(__FILE__) . "/404.php");
	die();
}

include_once(dirname(__FILE__) . '/../templates/common/header.php');

$photos = API\getPetPhotos($pet['id']);
$posts = API\getPosts($pet['id']);
?>

<section class='petProfile' data-id="<?= $pet['id'] ?>">
	<div style="background-image: url(<?= '../images/petProfilePictures/' . $pet['id'] . '.jpg' ?>);"> </div>
	<div>
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
				<button id="favorite" class="simpleButton">Add to favorites</button>
				<?php
				$adopted = API\getAdopted($pet['id']);

				if ($adopted == false) {

					$proposedToAdopt = API\getProposedToAdopt(Session\getAuthenticatedUser()['id'], $pet['id']);

					if (count($proposedToAdopt) == 0) { ?>
						<button id="adopt" class="simpleButton contrastButton">Adopt</button>
					<?php
					} else { ?>
						<p>You've proposed to adopt! <button id="cancel" class="simpleButton contrastButton">Cancel</button></p>
				<?php
					}
				} else { ?>
					<p>This pet was adopted by <?= $adopted['name'] ?></p>
				<?php
				}
				?>
			</footer>
		<?php } ?>
	</div>
</section>
<div id="mySlider" class="ss-parent">
	<?php
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
<section id="comments">
	<h4>Comments</h4>
	<?php if (sizeof($posts) == 0) { ?>
		<p>This pet has no comments yet.</p>
		<?php } else {
		for ($i = 0; $i < count($posts); $i++) {
			$user = API\getUserById($posts[$i]['userId']); ?>
			<article class="comment">
				<div class="image" style="background-image: url('../../images/userProfilePictures/<?= $user['id'] ?>.jpg')"></div>
				<p><?= $posts[$i]['description'] ?></p>
				<span class="user"><?= $user['shortName'] ?></span>
				<span class="date"><?= $posts[$i]['postDate'] ?></span>
			</article>

		<?php }
	}
	if (Session\isAuthenticated()) {
		$user = Session\getAuthenticatedUser();
		?>
		<form>
			<h4>Add Comment</h4>
			<textarea name="text"></textarea>
			<input type="submit" class="contrastButton" />
		</form>
	<?php } else { ?>
		<h4>To add a comment</h4>
		<ul>
			<li><a href="<?= getRootUrl() ?>/signin" class="simpleButton">Sign In</a></li>
			<li><a href="<?= getRootUrl() ?>/signup" class="simpleButton contrastButton">Sign Up</a></li>
		</ul>
	<?php } ?>
</section>


<?php include_once(dirname(__FILE__) . '/../templates/common/footer.php'); ?>