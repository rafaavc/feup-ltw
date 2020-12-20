<?php
$pageTitle = "Discover";
$GLOBALS['section'] = 'discover';
require_once(dirname(__FILE__)."/../control/api/pet.php");
require_once(dirname(__FILE__)."/../control/api/user.php");

require_once(dirname(__FILE__) ."/../templates/common/header.php");
?>

<?php require_once(dirname(__FILE__)."/../templates/search_form.php"); ?>

<section id="petResults">
    <header><h2>Our Pets</h2></header>
</section>

<section id="userResults">
    <header><h2>Our Users</h2></header>
</section>

<?php
require_once(dirname(__FILE__) ."/../templates/common/footer.php");
?>


