
<section class="petProfileSection">
	<h3>Comments</h3>
	<?php if (sizeof($posts) == 0) { ?>
		<p>This pet has no comments yet.</p>
		<?php } else {
		foreach ($posts as $post) {
			$poster = API\getUserById($post['userId']); ?>
			<article class="comment" id="post-<?=$post['id']?>">
				<div class="image" style="background-image: url('../../images/user_profile_pictures/<?= $poster['id'] ?>.jpg')"></div>
				<div>
					<?php if ($post['userId'] == $originalOwner['id']) { ?><span class="tagLabel accent">Original Poster</span><?php } ?>
					<p class="description"><?= htmlentities($post['description']) ?></p>
					<footer>
						<?= elapsedTime(strtotime($post['postDate'])) ?> ago, by <a href="<?=getRootUrl()?>/user/<?=htmlentities($poster['username'])?>"><?=htmlentities($poster['shortName'])?></a>
					</footer>
				</div>
			</article>

		<?php }
	}
	if (Session\isAuthenticated()) {
		$user = Session\getAuthenticatedUser();
		?>
		<form>
			<h4>Add Comment</h4>
			<textarea id="commentInput" name="text" minlength="1"></textarea>
			<input type="submit" class="contrastButton" />
		</form>
	<?php } else { ?>
		<p class="addCommentCTA">To add a comment,</p>
		<ul>			
			<li><a href="<?= getRootUrl() ?>/signup" class="simpleButton contrastButton">Sign Up</a></li>
			<li><a href="<?= getRootUrl() ?>/signin" class="simpleButton">Sign In</a></li>
		</ul>
	<?php } ?>
</section>
