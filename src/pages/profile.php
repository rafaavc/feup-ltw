<?php
$GLOBALS['section'] = 'profile';
include_once(dirname(__FILE__)."/../control/api/user.php");
include_once(dirname(__FILE__)."/../control/api/pet.php");
include_once(dirname(__FILE__) . "/../templates/common/header.php");

$user = API\getUserByUsername($GLOBALS['username']);

include_once(dirname(__FILE__) . "/../templates/profile/profile_page_header.php");
include_once(dirname(__FILE__) . "/../templates/show_pets.php");

$userLists = API\getUserLists($user['id']);
// this is the username of the user to show as given in the router: $GLOBALS['username'];
include_once(dirname(__FILE__) ."/../templates/common/header.php");
include_once(dirname(__FILE__) ."/../templates/profile/profile_page_header.php");
?>

<section class="petlist">
    <h1>Pets</h1>
    <?php showPetList(API\getUserPets($user['id'])) ?>
</section>

<section class="petlist">
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
            <div name="<?=$userList['title']?>"><?=showPetList(API\getListPets($userList))?></div>
        <?php
        } 
        ?>
    </div>
</section>

<?php
include_once(dirname(__FILE__) . "/../templates/common/footer.php");
?>