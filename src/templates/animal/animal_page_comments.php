
<section id="comments">
	<h4>Comments</h4>
	<?php if (sizeof($posts) == 0) { ?>
		<p>This pet has no comments yet.</p>
		<?php } else {
		foreach ($posts as $post) {
			$user = API\getUserById($post['userId']); ?>
			<article class="comment" id="post-<?=$post['id']?>">
				<div class="image" style="background-image: url('../../images/userProfilePictures/<?= $user['id'] ?>.jpg')"></div>
				<p><?= $post['description'] ?></p>
				<span class="user"><?= htmlentities($user['shortName']) ?></span>
				<span class="date"><?= elapsedTime(strtotime($post['postDate'])) ?> ago</span>
			</article>

		<?php }
	}
	if (Session\isAuthenticated()) {
		$user = Session\getAuthenticatedUser();
		?>
		<form>
			<h4>Add Comment</h4>
			<textarea id="commentInput" name="text"></textarea>
			<input type="submit" class="contrastButton" />
		</form>
	<?php } else { ?>
		<h4>To add a comment</h4>
		<ul>			
			<li><a href="<?= getRootUrl() ?>/signup" class="simpleButton contrastButton">Sign Up</a></li>
			<li><a href="<?= getRootUrl() ?>/signin" class="simpleButton">Sign In</a></li>
		</ul>
	<?php } ?>
</section>
