<?php
// this is the username of the user to show as given in the router: $GLOBALS['username'];
include_once(dirname(__FILE__) ."/../templates/common/header.php");
include_once(dirname(__FILE__) ."/../templates/profile/profile_page_header.php");
?>

<section id="posts">
    <h1>Posts</h1>
    <?php include_once(dirname(__FILE__) ."/../templates/show_pets.php");?>
</section>

<section id="favorites">
    <h1>Favorites</h1>
    <?php include_once(dirname(__FILE__) ."/../templates/show_pets.php");?>
</section>

<?php
include_once(dirname(__FILE__) ."/../templates/common/footer.php");
?>