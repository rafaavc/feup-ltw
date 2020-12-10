<?php
$user = getUserByUsername($GLOBALS['username']);
include_once(dirname(__FILE__) . "/../templates/common/header.php");
include_once(dirname(__FILE__) . "/../templates/profile/profile_page_header.php");
include_once(dirname(__FILE__) . "/../templates/show_pets.php");
?>

<section class="petlist">
    <h1>Pets</h1>
    <?php showPetList(getUserPets($user['id'])) ?>
</section>

<section class="petlist">
    <div id="select">
        <label for="list-select">
            <h1>Lists:</h1>
        </label>
        <select name="pets" id="list-select">
            <option value="favorites">Favorites</option>
            <option value="cat">Cat</option>
            <option value="hamster">Hamster</option>
        </select>
    </div>
    <?php //showPetList() ?> 
</section>

<?php
include_once(dirname(__FILE__) . "/../templates/common/footer.php");
?>