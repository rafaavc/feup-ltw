<section class='petProfile' data-id="<?= $pet['id'] ?>">
	<div style="background-image: url(<?= '../images/petProfilePictures/' . $pet['id'] . '.jpg' ?>);"> </div>
	<div>
		<header>
			<section class="textButtonPair">
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
					<button class="edit" id="nameAgeEdit"><i class="icofont-ui-edit"></i></button>
				</div>
				<form id="nameAgeForm">
					<input type="text" id="nameInput" value="<?= $pet['name'] ?>" />
					<input type="text" id="birthdateInput" name="birthdate" min="2000-01-01" max="<?= date('Y-m-d') ?>" placeholder="Birthdate" value="<?= $pet['birthdate'] ?>" />
					<button class="confirm" name="nameConfirm"><i class="icofont-ui-check"></i></button>
					<button class="close" name="nameAgeClose"><i class="icofont-ui-close"></i></button>
				</form>
			</section>
			<section class="textButtonPair">
				<div id="colorRaceLocation">
					<h4><?php
						if ($pet['race'] != null) {
							echo $pet['color'] . ' ' . $pet['race'] . ', ' . $pet['location'];
						} else if ($pet['specie'] != null) {
							echo $pet['color'] . ' ' . $pet['specie'] . ', ' . $pet['location'];
						}
						?></h4>
					<button class="edit" id="colorRaceLoactionEdit"><i class="icofont-ui-edit"></i></button>
				</div>
				<form id="colorRaceLocationForm">
					<input type="text" id="colorInput" value="<?= $pet['color'] ?>" />
					<input type="text" id="specieInput" value="<?= $pet['specie'] ?>" />
					<input type="text" id="raceInput" value="<?= $pet['race'] ?>" />
					<input type="text" id="locationInput" value="<?= $pet['location'] ?>" />
					<button class="confirm" name="colorSpeciesRaceLocationConfirm"><i class="icofont-ui-check"></i></button>
					<button class="close" name="colorSpeciesRaceLocationClose"><i class="icofont-ui-close"></i></button>
				</form>
			</section>
			<section class="textButtonPair">
				<div id="description">
					<p><?= $pet['description'] ?></p>
					<button class="edit" id="descriptionEdit"><i class="icofont-ui-edit"></i></button>
				</div>
				<form id="descriptionForm">
					<input type="textarea" id="descriptionInput" value="<?= $pet['description'] ?>" />
					<button class="confirm" name="descriptionConfirm"><i class="icofont-ui-check"></i></button>
					<button class="close" name="descriptionClose"><i class="icofont-ui-close"></i></button>
				</form>
			</section>

			<div id="photosInput" style="display: none">
				<input type="hidden" name="profilePhoto" />
				<div class="photos"></div>
				<input type="file" name="photos[]" />
				<button class="simpleButton" id="addPhotoButton"><i class="icofont-ui-add"></i>Photo</button>
				<p>To select a profile picture, click on one of the pictures you uploaded.</p>
			</div>
		</header>
		<?php if (Session\isAuthenticated()) {
			include_once(dirname(__FILE__) . '/animal_page_buttons.php');
		} ?>
	</div>
</section>