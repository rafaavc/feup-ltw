<footer>
	<button id="favorite" class="simpleButton">Add to favorites</button>
	<?php
    $adopted = API\getAdopted($pet['id']);

    if ($adopted == false) {
        $proposedToAdopt = API\getProposedToAdopt($user['id'], $pet['id']);

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

    if ($pet['userId'] == $user['id']) { ?>
		<button id="editPet" class="simpleButton">Edit Pet</button>
		<button id="closeEditPet" class="simpleButton" style="display: none">Close Edition</button>
	<?php
    }
    ?>
</footer>