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
$user = Session\getAuthenticatedUser();

include_once(dirname(__FILE__) . '/../templates/animal/animal_page_petProfile.php');
?>

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

<?php
include_once(dirname(__FILE__) . '/../templates/animal/animal_page_comments.php');
include_once(dirname(__FILE__) . '/../templates/common/footer.php');
?>