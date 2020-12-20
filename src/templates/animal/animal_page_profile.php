<section class="petProfile" data-id="<?= $pet['id'] ?>" data-owner-id="<?=$pet['userId']?>">
	<div style="background-image: url(<?= '../images/pet_profile_pictures/' . $pet['id'] . '.jpg' ?>);"></div>
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
			<p>by <a href="<?=getRootURL()?>/user/<?=htmlentities($originalOwner['username'])?>"><?=htmlentities($originalOwner['shortName'])?></a></p>
			<span class="tagLabel <?=$pet['state']?>"><?=$pet['state'] == 'adopted' ? 'Adopted' : ($pet['state'] == 'ready' ? 'Ready for Adoption' : ($pet['state'] == 'archived' ? 'Archived' : null)) ?></span>
			<p><?= htmlentities($pet['description']) ?></p>
		</div>
		<form method="POST" name="updatePet" action="<?= getRootURL() ?>/action/updatePet" enctype="multipart/form-data" id="updatePet">
			<input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>" />
			<input type="hidden" id="petId" name="petId" value="<?= $pet['id'] ?>" />
			<input type="text" id="nameInput" name="name" maxlength="20" value="<?= htmlentities($pet['name']) ?>" />
			<input type="text" id="locationInput" name="location" title="Location may only contain letters and spaces, there can not be 2 spaces in a row nor int the beginning/end." pattern="[a-zA-Z]+( [a-zA-Z]+)*" minlength="5" maxlength="20" value="<?= htmlentities($pet['location']) ?>" />
			<textarea id="descriptionInput" name="description" minlength="20" maxlength="300"><?= htmlentities($pet['description']) ?></textarea>
			<input type="hidden" name="profilePhoto" />
			
			<div id="photosInput" style="display: none">
				<div class="photos">
					<?php foreach ($photos as $photo) { ?>
						<div id="photo<?= $photo['photoId'] ?>">
							<img src="<?= getRootURL() ?>/images/pet_pictures/<?= $photo['photoId'] ?>.jpg" height=50 data-photo-id="<?= $photo['photoId'] ?>" alt="Pet picture">
							<div class="remove" data-id="<?= $photo['photoId'] ?>"><i class="icofont-ui-close"></i></div>
						</div>
					<?php } ?>
				</div>
				<input type="file" name="photos[]" accept="image/jpeg" />
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