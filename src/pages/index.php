<?php
include_once("templates/common/header.php");
include_once("templates/index/index_cover.php");
include_once("templates/index/index_follow_up.php");
include_once("templates/show_pets.php");
?>
<section>
    <h1>Meet Our Pets</h1>
    <?php  showPetList(getAllPets())?>
</section>

<?php
include_once("templates/common/footer.php");
?>