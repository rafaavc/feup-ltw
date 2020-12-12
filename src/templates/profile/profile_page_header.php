<section class="profile_header">
    <img src="<?=getRootURL()?>/images/userProfilePictures/<?=$user['id']?>.jpg" />
    <div>
        <header>
            <section class="textButtonPair">
                <div id="name">
                    <h2><?=htmlentities($user['name'])?></h2>
                    <button class="edit" id="nameEdit" onclick="showField('nameForm', 'name')"><i class="icofont-ui-edit"></i></button> 
                </div>
                <form id="nameForm">
                    <input type="text" />
                    <button class="confirm" name="nameConfirm" onclick="confirmSelection('<?=getRootURL()?>', 'nameForm', 'name', '<?=$user['username']?>')"><i class="icofont-ui-check"></i></button>
                    <button class="close" name="nameClose" onclick="resetSelection('nameForm', 'name')"><i class="icofont-ui-close"></i></button>
                </form>
            </section>
            <section class="textButtonPair">
                <div id="username">
                    <strong>@<?=htmlentities($user['username']) ?></strong>
                    <button class="edit" id="usernameEdit" onclick="showField('usernameForm', 'username')"><i class="icofont-ui-edit"></i></button>
                </div>
                <form id="usernameForm">
                    <input type="text" />
                    <button class="confirm" name="usernameConfirm" onclick="confirmSelection('<?=getRootURL()?>', 'usernameForm', 'username', '<?=$user['username']?>')"><i class="icofont-ui-check"></i></button> 
                    <button class="close" name="usernameClose" onclick="resetSelection('usernameForm', 'username')"><i class="icofont-ui-close"></i></button>
                </form>
            </section>
            <section class="textButtonPair">
                <div id="mail">
                    <strong><?=htmlentities($user['mail']) ?></strong>
                    <button class="edit" id="mailEdit" onclick="showField('mailForm', 'mail')"><i class="icofont-ui-edit"></i></button>
                </div>
                <form id="mailForm">
                    <input type="text" />
                    <button class="confirm" name="mailConfirm" onclick="confirmSelection('<?=getRootURL()?>', 'mailForm', 'mail', '<?=$user['username']?>')"><i class="icofont-ui-check"></i></button>
                    <button class="close" name="mailClose" onclick="resetSelection('mailForm', 'mail')"><i class="icofont-ui-close"></i></button>
                </form>
            </section>
        </header>
        <section class="textButtonPair" id="bioSection">
            <div id="bio">
                <p><?=htmlentities($user['description']) ?></p>
                <button class="edit" id="bioEdit" onclick="showField('bioForm', 'bio')"><i class="icofont-ui-edit"></i></button>
            </div>
            <form id="bioForm">
                <input type="text" />
                <button class="confirm" name="bioConfirm" onclick="confirmSelection('<?=getRootURL()?>', 'bioForm', 'bio', '<?=$user['username']?>')"><i class="icofont-ui-check"></i></button> 
                <button class="close" onclick="resetSelection('bioForm', 'bio')"><i class="icofont-ui-close"></i></button>
            </form>
        </section>
    </div>
    <aside>
        <label for="editProfile" id="editProfileLabel">
            <a class="simpleButton contrastButton">Edit Profile</a>
        </label>
        <input type="checkbox" id="editProfile" onclick="editProfile()"/> 
    </aside>
</section>