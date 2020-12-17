<section id="petProposals">
	<?php
	$openProposals = API\getPetOpenAdoptionProposals($pet['id']);
	$rejectedProposals = API\getPetRejectedProposals($pet['id']);
	if ($openProposals != false || $rejectedProposals != false) { ?>
		<h3>Adoption Proposals</h3>
		<?php
		if ($openProposals != false) {
			foreach ($openProposals as $proposal) {
		?>
				<div class="petProposal">
					<div class="image" style="background-image: url('../../images/userProfilePictures/<?= $proposal['userId'] ?>.jpg')"></div>
					<p><a href="<?= getRootUrl() ?>/user/<?= $proposal['username'] ?>"><?= $proposal['fullName'] ?></a> wants to adopt this pet</p>
				</div>
			<?php
			}
		}
		if ($rejectedProposals != false) {
			$owner = API\getUserById($pet['userId']);
			foreach ($rejectedProposals as $proposal) {
			?>
				<div class="petProposal">
					<div class="image" style="background-image: url('../../images/userProfilePictures/<?= $proposal['userId'] ?>.jpg')"></div>
					<p><a href="<?= getRootUrl() ?>/user/<?= $proposal['username'] ?>"><?= $proposal['fullName'] ?></a>'s got rejected by <a href="<?= getRootUrl() ?>/user/<?= $owner['username'] ?>"><?= $owner['name'] ?></a></p>
				</div>
	<?php
			}
		}
	}
	?>

</section>