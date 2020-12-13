
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