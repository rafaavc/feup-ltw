<?php
$GLOBALS['section'] = 'signup';
include_once(dirname(__FILE__) ."/../templates/common/header.php");

if (Session\isAuthenticated()) { ?>
    <section>
        <h3>You are already signed up.</h3>
    </section>
    <?php } else { ?>

<section class="authForm">
    <div>
        <h1>Sign Up</h1>

        <?php 
            $message = Session\popMessage(); 
            if ($message != null) { ?>
                <p class="<?=$message['type']?>-message"><?=$message['content']?></p>
        <?php } ?>
                
        <p class="notice">All fields marked with * are required.</p>

        <form method="POST" action="<?=getRootURL()?>/control/actions/register.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>" />
            <div class="formField required">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" minlength="1" maxlength="40" placeholder="Full Name" required/>
            </div>
            
            <div class="formField required">
                <label for="birthdate">Birthdate</label>
                <input type="text" id="birthdate" name="birthdate" min="1900-01-01" max="<?=date("Y-m-d", strtotime(date("Y-m-d").' -18 years'))?>" placeholder="Birthdate" required/>
            </div>

            <div class="formField required">
                <label for="mail">E-Mail</label>
                <input type="email" id="mail" pattern="[a-zA-Z0-9_.@]+" name="mail" placeholder="E-Mail" required/>
            </div>

            <div class="formField">
                <label for="description">Bio</label>
                <textarea placeholder="Bio" name="description" maxlength="300" id="description"></textarea>
            </div>

            <div class="formField required">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" minlength="5" maxlength="15" placeholder="Username" required/>
            </div>

            <div class="formField required">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" minlength="8" placeholder="Password" required/>
            </div>

            <label for="profilePhoto">Profile Photo</label> 
            <input type="file" name="profilePhoto" accept="image/jpeg" id="profilePhoto" />
            <div class="photoContainer"></div>

            <div class="formField required">
                <button class='simpleButton' id='profilePhotoButton'><i class="icofont-ui-add"></i>Profile Photo</button>
            </div>

            <input type="submit" value="Sign Up" />
        </form>
    </div>
    <img src="<?=getRootUrl()?>/images/cuteDoggos.jpeg"/>
</section>

<?php }

include_once(dirname(__FILE__) ."/../templates/common/footer.php"); ?>
