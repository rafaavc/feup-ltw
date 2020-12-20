<section class="profileHeader">
    <div style="background-image: url(<?= '../images/user_profile_pictures/' . $user['id'] . '.jpg' ?>);"> </div>
    <div>
        <header>
            <div class="textButtonPair">
                <div id="name">
                    <h3><?=htmlentities($user['name'])?></h3>
                    <button class="edit clickable" id="nameEdit"><i class="icofont-ui-edit"></i></button>
                </div>
                <form id="nameForm">
                    <input type="text" class="edit-data" maxlength="20" required/>
                    <button class="confirm" name="nameConfirm"><i class="icofont-ui-check"></i></button>
                    <button class="close" name="nameClose"><i class="icofont-ui-close"></i></button>
                </form>
            </div>
            <div class="textButtonPair">
                <div id="username">
                    <strong><?=htmlentities($user['username']) ?></strong>
                    <button class="edit clickable" id="usernameEdit"><i class="icofont-ui-edit"></i></button>
                </div>
                <form id="usernameForm">
                    <input type="text" class="edit-data" minlength="5" maxlength="15" required/>
                    <button class="confirm" name="usernameConfirm"><i class="icofont-ui-check"></i></button>
                    <button class="close" name="usernameClose"><i class="icofont-ui-close"></i></button>
                </form>
            </div>
            <div class="textButtonPair">
                <div id="mail">
                    <p><?=htmlentities($user['mail']) ?></p>
                    <button class="edit clickable" id="mailEdit"><i class="icofont-ui-edit"></i></button>
                </div>
                <form id="mailForm">
                    <input type="email"  pattern="[a-zA-Z0-9_.@]+" class="edit-data" required/>
                    <button class="confirm" name="mailConfirm"><i class="icofont-ui-check"></i></button>
                    <button class="close" name="mailClose"><i class="icofont-ui-close"></i></button>
                </form>
            </div>
        </header>
        <div class="textButtonPair" id="bioSection">
            <div id="bio">
                <p><?=htmlentities($user['description']) ?></p>
                <button class="edit clickable" id="bioEdit"><i class="icofont-ui-edit"></i></button>
            </div>
            <form id="bioForm">
                <textarea name="text" maxlength="300"></textarea>
                <button class="confirm" name="bioConfirm"><i class="icofont-ui-check"></i></button>
                <button class="close" name="bioClose"><i class="icofont-ui-close"></i></button>
            </form>
        </div>
        <div class="textButtonPair" id="passwordSection">
            <div id="password">
                <p class="edit">Password</p>
                <button class="edit clickable" id="passwordEdit"><i class="icofont-ui-edit"></i></button>
            </div>
            <form id="passwordForm">
                <label for="currentPassword">Current Password</label>
                <input type="password" id="currentPassword" name="currentPassword" placeholder="Current Password" required/>

                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="New Password" required/>

                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required/>

                <button class="confirm" name="passwordConfirm"><i class="icofont-ui-check"></i></button>
                <button class="close" name="passwordClose"><i class="icofont-ui-close"></i></button>
            </form>
        </div>
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