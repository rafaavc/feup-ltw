<footer>
	<select id="selectList">
		<?php
		$user = Session\getAuthenticatedUser();
		$userLists = API\getUserLists($user['id']);

		foreach ($userLists as $userList) {
		?>
			<option class="listOption" value="<?= htmlentities($userList['id']) ?>"><?= htmlentities($userList['title']) ?></option>
		<?php
		}
		?>
	</select>
	<button id ="addToList" class="simpleButton">Add to list</button>
	<?php
	$adopted = API\getAdopted($pet['id']);

	if ($pet['userId'] == $user['id']) { ?>
		<button id="editPet" class="simpleButton contrastButton">Edit Pet</button>
		<button id="closeEdit" class="simpleButton contrastButton" style="display: none">Cancel Edition</button>
		<?php
	} else if ($adopted == false) {
		$proposedToAdopt = API\getProposedToAdopt($user['id'], $pet['id']);

		if ($proposedToAdopt == false) { ?>
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