<section class='petProfile' data-id="<?= $pet['id'] ?>">
	<div style="background-image: url(<?= '../images/petProfilePictures/' . $pet['id'] . '.jpg' ?>);"> </div>
	<div>
		<header>
			<section id="petInfo">
				<div id="nameAge">
					<h3><?= $pet['name'] == null ? '' : $pet['name'] . ',' ?>
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
						} else {
							if ($years == 0)
								echo '0 months';
						}
						?>
					</h3>
				</div>
				<div id="colorRaceLocation">
					<h4><?php
						if ($pet['race'] != null) {
							echo $pet['color'] . ' ' . $pet['race'] . ', ' . $pet['location'];
						} else if ($pet['specie'] != null) {
							echo $pet['color'] . ' ' . $pet['specie'] . ', ' . $pet['location'];
						}
						?></h4>
				</div>
				<div id="description">
					<p><?= $pet['description'] ?></p>
				</div>
			</section>
			<form method="POST" name="updatePet" action="<?= getRootURL() ?>/control/actions/update_pet.php" enctype="multipart/form-data" id="updateForm">
				<input type="hidden" id="petId" name="pet" value="<?= $pet['id'] ?>" />
				<input type="text" id="nameInput" name="name" value="<?= $pet['name'] ?>" />
				<input type="text" id="birthdateInput" name="birthdate" min="2000-01-01" max="<?= date('Y-m-d') ?>" placeholder="Birthdate" value="<?= $pet['birthdate'] ?>" />
				<input type="text" id="colorInput" name="color" value="<?= $pet['color'] ?>" />
				<input type="text" id="specieInput" name="specie" value="<?= $pet['specie'] ?>" />
				<input type="text" id="raceInput" name="race" value="<?= $pet['race'] ?>" />
				<input type="text" id="locationInput" name="location" value="<?= $pet['location'] ?>" />
				<textarea id="descriptionInput" name="description"><?= $pet['description'] ?></textarea>

				<div id="photosInput" style="display: none">
					<input type="hidden" name="profilePhoto" />
					<div class="photos">
						<?php
						foreach ($photos as $photo) {
						?>
							<div>
								<img src="<?= getRootURL() ?>/images/petPictures/<?= $photo['photoId'] ?>.jpg" heigth=50>
								<div class="remove"><i class="icofont-ui-close"></i></div>
							</div>
						<?php
						}
						?>
					</div>
					<input type="file" name="photos[]" />
					<button class="simpleButton" id="addPhotoButton"><i class="icofont-ui-add"></i>Photo</button>
					<p>To select a profile picture, click on one of the pictures you uploaded.</p>
				</div>

				<input type="submit" id="closeEditPet" value="Confirm Edition" style="display: none" /></input>
			</form>
		</header>
		<?php if (Session\isAuthenticated()) {
			include_once(dirname(__FILE__) . '/animal_page_buttons.php');
		} ?>
	</div>
</section>