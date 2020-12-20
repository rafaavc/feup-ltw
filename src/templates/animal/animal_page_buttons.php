<footer>
	<?php if ($user != false) { ?>
		<?php
		$userLists = API\getUserLists($user['id']);
		if (sizeof($userLists) != 0) { ?>
		<select name="lists">
			<?php foreach ($userLists as $userList) { ?>
				<option class="listOption" value="<?= htmlentities($userList['id']) ?>"><?= htmlentities($userList['title']) ?></option>
			<?php } ?>
		</select>
		<button id ="addToList" class="simpleButton">Add to list</button>
	<?php } else { ?>
		<p class="notice">To add this pet to a list, create one in your <a href="<?=getRootUrl()?>/user/<?=htmlentities($user['username'])?>">profile</a>.</p>

	<?php }
	}
	$adopted = API\getAdopted($pet['id']);

	if ($user != false && $pet['userId'] == $user['id']) { ?>
		<button id="editPet" class="simpleButton contrastButton">Edit Pet</button>
		<button id="closeEdit" class="simpleButton contrastButton" style="display: none">Cancel Edition</button>
		<?php
	} else if ($adopted == false) {
		$proposedToAdopt = API\getProposedToAdopt($user['id'], $pet['id']);

		if ($proposedToAdopt == false) { ?>
			<button id="adopt" class="simpleButton contrastButton" data-user-id="<?= $user['id'] ?>" data-username="<?= htmlentities($user['username']) ?>" data-user-name="<?= htmlentities($user['name']) ?>">Adopt</button>
		<?php
		} else { ?>
			<p>You've proposed to adopt! <button id="cancel" class="simpleButton contrastButton" data-user-id="<?= $user['id'] ?>" data-username="<?= htmlentities($user['username']) ?>" data-user-name="<?= htmlentities($user['name']) ?>">Cancel</button></p>
		<?php
		}
	}
	if ($adopted) { ?>
		<p>This pet was adopted by <a href="<?= getRootUrl() ?>/user/<?= htmlentities($adopted['username']) ?>"><?= htmlentities($adopted['name']) ?></a>.</p>
	<?php
	}
	?>
</footer>