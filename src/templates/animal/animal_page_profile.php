<section class="petProfile" data-id="<?= $pet['id'] ?>" data-owner-id="<?=$pet['userId']?>">
	<div style="background-image: url(<?= '../images/petProfilePictures/' . $pet['id'] . '.jpg' ?>);"></div>
	<div>
		<div id="petInfo">
			<header>
				<h3>
					<?= $pet['name'] == null ? '' : htmlentities($pet['name']).',' ?>
					<?=elapsedTime(strtotime($pet['birthdate']))?> old
				</h3>
				<h4>
					<?php if ($pet['race'] != null) {
						echo htmlentities($pet['size']) . ' ' . htmlentities($pet['color']) . ' ' . htmlentities($pet['race']) . ', ' . htmlentities($pet['location']);
					} else if ($pet['specie'] != null) {
						echo htmlentities($pet['size']) . ' ' . htmlentities($pet['color']) . ' ' . htmlentities($pet['specie']) . ', ' . htmlentities($pet['location']);
					} ?>
				</h4>
			</header>
			<p>by <a href="<?=getRootURL()?>/user/<?=$originalOwner['username']?>"><?=$originalOwner['shortName']?></a></p>
			<span class="tagLabel <?=$pet['state']?>"><?=$pet['state'] == 'adopted' ? 'Adopted' : ($pet['state'] == 'ready' ? 'Ready for Adoption' : ($pet['state'] == 'archived' ? 'Archived' : null)) ?></span>
			<p><?= $pet['description'] ?></p>
		</div>
		<form method="POST" name="updatePet" action="<?= getRootURL() ?>/action/updatePet" enctype="multipart/form-data" id="updatePet">
			<input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>" />
			<input type="hidden" id="petId" name="petId" value="<?= $pet['id'] ?>" />
			<input type="text" id="nameInput" name="name" maxlength="20" value="<?= $pet['name'] ?>" />
			<input type="text" id="locationInput" name="location" minlength="5" maxlength="20" value="<?= $pet['location'] ?>" />
			<textarea id="descriptionInput" name="description" minlength="20" maxlength="300"><?= $pet['description'] ?></textarea>
			<input type="hidden" name="profilePhoto" />
			
			<div id="photosInput" style="display: none">
				<div class="photos">
					<?php foreach ($photos as $photo) { ?>
						<div id="photo<?= $photo['photoId'] ?>">
							<img src="<?= getRootURL() ?>/images/petPictures/<?= $photo['photoId'] ?>.jpg" heigth=50 data-photo-id="<?= $photo['photoId'] ?>">
							<div class="remove" data-id="<?= $photo['photoId'] ?>"><i class="icofont-ui-close"></i></div>
						</div>
					<?php } ?>
				</div>
				<input type="file" name="photos[]" />
				<button class="simpleButton" id="addPhotoButton"><i class="icofont-ui-add"></i>Photo</button>
				<p>To select a profile picture, click on one of the pictures you uploaded.</p>
			</div>

			<input type="submit" id="submitEditPet" value="Confirm Edition" style="display: none" />
		</form>
		<?php if (Session\isAuthenticated()) {
			require_once(dirname(__FILE__) . '/animal_page_buttons.php');
		} ?>
		<p id="tempText"></p>
	</div>
</section>