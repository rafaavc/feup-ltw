<?php
$GLOBALS['section'] = 'discover';
require_once(dirname(__FILE__) . '/../control/db.php');
require_once(dirname(__FILE__) . "/../control/api/pet.php");

$pet = API\getPet($GLOBALS['id']);
if (!$pet) Router\error404();

$pageTitle = htmlentities(API\getPetName($pet['id']));
require_once(dirname(__FILE__) . '/../templates/common/header.php');

$photos = API\getPetPhotos($pet['id']);
$posts = API\getPosts($pet['id']);
$user = Session\getAuthenticatedUser();
$originalOwner = API\getUserById($pet['userId']);

require_once(dirname(__FILE__) . '/../templates/animal/animal_page_profile.php');
?>

<div id="mySlider" class="ss-parent">
	<?php
	for ($i = 0; $i < count($photos); $i++) {
	?>
		<div class="ss-child" id="ss-<?= $photos[$i]['photoId'] ?>">
			<div style="background: url(<?= getRootURL() ?>/images/pet_pictures/<?= $photos[$i]['photoId'] ?>.jpg); background-size: cover; background-position: 50%"> </div>
		</div>
	<?php
	}
	?>
	<div class="ss-nav"></div>
</div>

<?php
require_once(dirname(__FILE__) . '/../templates/animal/animal_page_proposals.php');
require_once(dirname(__FILE__) . '/../templates/animal/animal_page_comments.php');
require_once(dirname(__FILE__) . '/../templates/common/footer.php');
?>