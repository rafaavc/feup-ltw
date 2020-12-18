<section class="profile_header">
    <div style="background-image: url(<?= '../images/userProfilePictures/' . $user['id'] . '.jpg' ?>);"> </div>
    <div>
        <header>
            <section class="textButtonPair">
                <div id="name">
                    <h3><?=htmlentities($user['name'])?></h3>
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
                    <strong><?=htmlentities($user['username']) ?></strong>
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
                    <p><?=htmlentities($user['mail']) ?></p>
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
                <textarea name="text"></textarea>
                <button class="confirm" name="bioConfirm"><i class="icofont-ui-check"></i></button> 
                <button class="close" name="bioClose"><i class="icofont-ui-close"></i></button>
            </form>
        </section>
    <?php
    if (Session\isAuthenticated() && $user['username'] == Session\getAuthenticatedUser()['username']) {
    ?>
        <footer>
            <input type="checkbox" id="editProfile"/>
            <label for="editProfile" id="editProfileLabel">
                <a class="simpleButton contrastButton">Edit Profile</a>
            </label>
        </footer>
    <?php
    }
    ?>
    </div>
</section>