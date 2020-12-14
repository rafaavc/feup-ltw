<?php
$GLOBALS['section'] = 'profile';
include_once(dirname(__FILE__)."/../control/api/user.php");
include_once(dirname(__FILE__)."/../control/api/pet.php");
include_once(dirname(__FILE__) . "/../templates/common/header.php");

$user = API\getUserByUsername($GLOBALS['username']);

include_once(dirname(__FILE__) . "/../templates/profile/profile_header.php");
include_once(dirname(__FILE__) . "/../templates/show_pets.php");

$userLists = API\getUserLists($user['id']);
?>

<section id="userPets" class="petlist">
    <header><h2>Pets</h2></header>
    <div class="petGrid">
        <div class="arrow left"></div>
        <div class="petGridContent"></div>
        <div class="arrow right"></div>
    </div>
</section>

<section id="userList" class="petlist">
    <div id="select">
        <label for="list-select">
            <h1>Lists:</h1>
        </label>
        <select name="pets" id="list-select">
            <?php
            foreach($userLists as $userList) {
            ?>
                <option value="<?=htmlentities($userList['id'])?>"><?=htmlentities($userList['title'])?></option>
            <?php
            }
            ?>
        </select>
    </div>


    <div id="lists">
        <?php
        foreach($userLists as $userList){
        ?>
            <div name="<?=$userList['title']?>" class="petGrid">
                <div class="arrow left"></div>
                <div class="petGridContent"></div>
                <div class="arrow right"></div>
            </div>
        <?php
        } 
        ?>
    </div>
</section>

<?php
include_once(dirname(__FILE__) . "/../templates/common/footer.php");
?>