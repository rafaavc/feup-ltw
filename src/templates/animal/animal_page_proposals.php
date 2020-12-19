
<?php
$openProposals = API\getPetOpenAdoptionProposals($pet['id']);
$rejectedProposals = API\getPetRejectedProposals($pet['id']);
?>

<section class="petProfileSection" id="petProposals" data-user-id="<?= $user['id'] ?>">
	<h3>Adoption Proposals</h3>
	<?php
	if ($openProposals != false || $rejectedProposals != false) { ?>
		<?php
		if ($openProposals != false) {
			foreach ($openProposals as $proposal) {
		?>
				<div class="petProposal open" data-id="<?= $proposal['userId']?>">
					<div class="image" style="background-image: url('../../images/userProfilePictures/<?= $proposal['userId'] ?>.jpg')"></div>
					<p><a href="<?= getRootUrl() ?>/user/<?= $proposal['username'] ?>"><?= htmlentities($proposal['fullName']) ?></a> wants to adopt this pet</p>
				</div>
			<?php
			}
		}
		if ($rejectedProposals != false) {
			foreach ($rejectedProposals as $proposal) {
			?>
				<div class="petProposal rejected">
					<div class="image" style="background-image: url('../../images/userProfilePictures/<?= $proposal['userId'] ?>.jpg')"></div>
					<p><a href="<?= getRootUrl() ?>/user/<?= $proposal['username'] ?>"><?= htmlentities($proposal['fullName']) ?></a>'s proposal got rejected by <a href="<?= getRootUrl() ?>/user/<?= $originalOwner['username'] ?>"><?= htmlentities($originalOwner['name']) ?></a></p>
				</div>
	<?php
			}
		}
	} else {
		?>
		<p>This pet hasn't gotten any adoption proposals yet.</p>
		<?php
	}
	?>

</section>