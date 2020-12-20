<?php
$GLOBALS['section'] = 'profile';
require_once(dirname(__FILE__)."/../control/api/user.php");
require_once(dirname(__FILE__)."/../control/api/pet.php");

$user = API\getUserByUsername($GLOBALS['username']);
if (!$user) Router\error404();
$pageTitle = htmlentities($user['shortName']);

require_once(dirname(__FILE__) . "/../templates/common/header.php");


$authenticatedUser = Session\getAuthenticatedUser();

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
        <header><h2>Lists</h2></header>
            <select name="list" id="listSelect">
                <?php
                foreach($userLists as $userList) {
                    if (($authenticatedUser && $authenticatedUser['username'] == $user['username'])
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
        if (($authenticatedUser && $authenticatedUser['username'] == $user['username'])) {
        ?>
        <div id="listButtons">
            <button class="simpleButton" id="addListButton" data-entity="List"><i class="icofont-ui-add"></i>New list</button>
            <?php
            if (!empty($userLists)) {
            ?>
                <button class="simpleButton" id="removeListButton" data-entity="List"><i class="icofont-ui-delete"></i>Delete list</button>   
            <?php
            }
            ?>    
        </div>
        <?php } ?>
    </div>

    <div id="lists">
        <?php
        $listAmount = sizeof($userLists);
        if ($user['username'] != $authenticatedUser['username']) {
            $listAmount = 0;
            foreach($userLists as $userList) {
                if ($userList['public']) $listAmount++;
            }
        }

        if ($listAmount != 0) {
            foreach($userLists as $userList){
                if (($authenticatedUser && $user['username'] == $authenticatedUser['username'])
                        || ($userList['public'] == 1)) {
            ?>
                <div class="list" data-id="<?=htmlentities($userList['id'])?>">
                    <p> <?=htmlentities($userList['description'])?> </p>
                    <div class="petGrid" >
                        <div class="arrow left"></div>
                        <div class="petGridContent"></div>
                        <div class="arrow right"></div>
                    </div>
                </div>
            <?php
                }
            } 
        } else { ?>
            <p>@<?=htmlentities($user['username'])?> has no <?=$user['username'] != $authenticatedUser['username'] ? 'public' : ''?> lists.</p>
        <?php } ?>
    </div>
</section>

<?php
require_once(dirname(__FILE__) . "/../templates/common/footer.php");
?>