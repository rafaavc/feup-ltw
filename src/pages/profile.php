<?php
$GLOBALS['section'] = 'profile';
require_once(dirname(__FILE__)."/../control/api/user.php");
require_once(dirname(__FILE__)."/../control/api/pet.php");
require_once(dirname(__FILE__) . "/../templates/common/header.php");

$user = API\getUserByUsername($GLOBALS['username']);
if (!$user) Router\error404();

require_once(dirname(__FILE__) . "/../templates/profile/profile_header.php");

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
    <div class="simple-2column-grid">
        <div id="select">
            <label for="list-select">
                <h1>Lists:</h1>
            </label>
            <select name="list" id="list-select">
                <?php
                foreach($userLists as $userList) {
                    if ((Session\isAuthenticated() && $user['username'] == Session\getAuthenticatedUser()['username'])
                        || ($userList['public'] == 1)) {
                ?>
                    <option value="<?=htmlentities($userList['id'])?>"><?=htmlentities($userList['title'])?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>
        <?php
        if ((Session\isAuthenticated() && $user['username'] == Session\getAuthenticatedUser()['username'])) {
        ?>
        <div>
            <button class="simpleButton" id="addListButton" data-entity="List"><i class="icofont-ui-add"></i>New list</button>
            <button class="simpleButton" id="removeListButton" data-entity="List"><i class="icofont-ui-delete"></i>Delete list</button>   
        </div>
        <?php
        }
        ?>
    </div>

    <div id="lists">
        <?php
        foreach($userLists as $userList){
            if ((Session\isAuthenticated() && $user['username'] == Session\getAuthenticatedUser()['username'])
                    || ($userList['public'] == 1)) {
        ?>
            <div class="petGrid" data-id="<?=htmlentities($userList['id'])?>">
                <div class="arrow left"></div>
                <div class="petGridContent"></div>
                <div class="arrow right"></div>
            </div>
        <?php
            }
        } 
        ?>
    </div>
</section>

<?php
require_once(dirname(__FILE__) . "/../templates/common/footer.php");
?>