<?php

use function API\getProposedToAdopt;

$GLOBALS['section'] = 'discover';
include_once(dirname(__FILE__) . '/../control/db.php');
include_once(dirname(__FILE__) . '/../templates/common/header.php');
require_once(dirname(__FILE__) . "/../control/api/pet.php");

$pet = API\getPet($GLOBALS['id']);
$photos = API\getPetPhotos($pet['id']);
$posts = API\getPosts($pet['id']);
?>

<section class='profile_header' data-id="<?= $pet['id'] ?>">
	<div style="background-image: url(<?= '../images/petProfilePictures/' . $pet['id'] . '.jpg' ?>);"> </div>
	<div>
		<header>
			<h3><?= $pet['name'] ?>, <?=elapsedTime(strtotime($pet['birthdate']))?></h3>
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
				$proposedToAdopt = getProposedToAdopt(Session\getAuthenticatedUser()['id'], $pet['id']);

				if (count($proposedToAdopt) == 0) { ?>
					<button id="adopt" class="simpleButton contrastButton">Adopt</button>
				<?php
				} else { ?>
					<p>You've proposed to adopt! <button id="cancel" class="simpleButton contrastButton">Cancel</button></p>
				<?php } ?>
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
			<article class="comment" id="post-<?=$posts[$i]['id']?>">
				<div class="image" style="background-image: url('../../images/userProfilePictures/<?= $user['id'] ?>.jpg')"></div>
				<p><?= $posts[$i]['description'] ?></p>
				<span class="user"><?= $user['shortName'] ?></span>
				<span class="date"><?= elapsedTime(strtotime($posts[$i]['postDate']))." ago" ?></span>
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