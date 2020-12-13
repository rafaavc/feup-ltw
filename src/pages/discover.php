<?php
$GLOBALS['section'] = 'discover';
require_once(dirname(__FILE__)."/../control/api/pet.php");
require_once(dirname(__FILE__)."/../control/api/user.php");

$pets = API\getArrayFromSTMT(API\getPets(), 10);
$users = API\getArrayFromSTMT(API\getUsers(), 10);

include_once(dirname(__FILE__) ."/../templates/common/header.php");
include_once(dirname(__FILE__) ."/../templates/tiles.php");
?>

<?php require_once(dirname(__FILE__)."/../templates/search_form.php"); ?>

<section id="petResults">
    <header><h2>Our Pets</h2></header>
    <div>
    </div>
</section>

<section id="userResults">
    <header><h2>Our Users</h2></header>
    <div>
    </div>
</section>

<?php
include_once(dirname(__FILE__) ."/../templates/common/footer.php");
?>


