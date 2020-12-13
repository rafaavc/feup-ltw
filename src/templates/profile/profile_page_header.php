<section class="profile_header">
    <img src="<?=getRootURL()?>/images/userProfilePictures/<?=$user['id']?>.jpg" />
    <div>
        <header>
            <section class="textButtonPair">
                <div id="name">
                    <h2><?=htmlentities($user['name'])?></h2>
                    <button class="edit" id="nameEdit"><i class="icofont-ui-edit"></i></button> 
                </div>
                <form id="nameForm">
                    <input type="text" />
                    <button class="confirm" name="nameConfirm"><i class="icofont-ui-check"></i></button>
                    <button class="close" name="nameClose"><i class="icofont-ui-close"></i></button>
                </form>
            </section>
            <section class="textButtonPair">
                <div id="username">
                    <strong>@<?=htmlentities($user['username']) ?></strong>
                    <button class="edit" id="usernameEdit"><i class="icofont-ui-edit"></i></button>
                </div>
                <form id="usernameForm">
                    <input type="text" />
                    <button class="confirm" name="usernameConfirm"><i class="icofont-ui-check"></i></button> 
                    <button class="close" name="usernameClose"><i class="icofont-ui-close"></i></button>
                </form>
            </section>
            <section class="textButtonPair">
                <div id="mail">
                    <strong><?=htmlentities($user['mail']) ?></strong>
                    <button class="edit" id="mailEdit"><i class="icofont-ui-edit"></i></button>
                </div>
                <form id="mailForm">
                    <input type="text" />
                    <button class="confirm" name="mailConfirm"><i class="icofont-ui-check"></i></button>
                    <button class="close" name="mailClose"><i class="icofont-ui-close"></i></button>
                </form>
            </section>
        </header>
        <section class="textButtonPair" id="bioSection">
            <div id="bio">
                <p><?=htmlentities($user['description']) ?></p>
                <button class="edit" id="bioEdit"><i class="icofont-ui-edit"></i></button>
            </div>
            <form id="bioForm">
                <input type="text" />
                <button class="confirm" name="bioConfirm"><i class="icofont-ui-check"></i></button> 
                <button class="close" name="bioClose"><i class="icofont-ui-close"></i></button>
            </form>
        </section>
    </div>
    <?php
    if (isset($_SESSION['username']) && $user['username'] == $_SESSION['username']) {
    ?>
        <aside>
            <label for="editProfile" id="editProfileLabel">
                <a class="simpleButton contrastButton">Edit Profile</a>
            </label>
            <input type="checkbox" id="editProfile"/> 
        </aside>
    <?php
    }
    ?>
</section>