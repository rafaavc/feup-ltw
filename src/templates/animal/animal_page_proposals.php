<section id="petProposals">
	<?php
	$openProposals = API\getPetOpenAdoptionProposals($pet['id']);
	$rejectedProposals = API\getPetRejectedProposals($pet['id']); ?>
	<h3>Adoption Proposals</h3>
	<?php
	if ($openProposals != false || $rejectedProposals != false) { ?>
		<?php
		if ($openProposals != false) {
			foreach ($openProposals as $proposal) {
		?>
				<div class="petProposal open" data-id="<?= $proposal['userId']?>">
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
				<div class="petProposal rejected">
					<div class="image" style="background-image: url('../../images/userProfilePictures/<?= $proposal['userId'] ?>.jpg')"></div>
					<p><a href="<?= getRootUrl() ?>/user/<?= $proposal['username'] ?>"><?= $proposal['fullName'] ?></a>'s got rejected by <a href="<?= getRootUrl() ?>/user/<?= $owner['username'] ?>"><?= $owner['name'] ?></a></p>
				</div>
	<?php
			}
		}
	} else {
		?>
		<p>This pet hasn't had any proposals to adopt</p>
		<?php
	}
	?>

</section>