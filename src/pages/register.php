<?php
$pageTitle = "Sign Up";
$GLOBALS['section'] = 'signup';
require_once(dirname(__FILE__) ."/../templates/common/header.php");

if (Session\isAuthenticated()) { ?>
    <section>
        <h3>You are already signed up.</h3>
    </section>
    <?php } else { ?>

<section class="authForm">
    <div>
        <h2>Sign Up</h2>
                
        <p class="notice">All fields marked with * are required.</p>

        <?php 
            $message = Session\popMessage(); 
            if ($message != null) { ?>
                <p class="<?=$message['type']?>-message"><?=$message['content']?></p>
        <?php } ?>

        <form method="POST" action="<?=getRootURL()?>/action/signup" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>" />
            <div class="formField required">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" minlength="1" maxlength="40" placeholder="Full Name" required/>
            </div>
            
            <div class="formField required">
                <p class="notice">Birthdate</p>
                <label for="birthdate">Birthdate</label>
                <input type="date" id="birthdate" name="birthdate" min="<?=date("Y-m-d", getYearsAgo(100))?>" max="<?=date("Y-m-d", getYearsAgo(18))?>" required/>
            </div>

            <div class="formField required">
                <label for="mail">E-Mail</label>
                <input type="email" id="mail" pattern="^([a-zA-Z0-9]+(_|\.))*[a-zA-Z0-9]+@([a-zA-Z0-9]+\.)*[a-zA-Z]+$" title="Must be a valid email" name="mail" placeholder="E-Mail" required/>
            </div>

            <div class="formField">
                <label for="description">Bio</label>
                <textarea placeholder="Bio" name="description" maxlength="300" id="description"></textarea>
            </div>

            <div class="formField required">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" pattern="^[a-zA-Z0-9]+((_|\.)[a-zA-Z0-9]+)*$" title="Must contain only letters numbers, '_' and '.', but the last two can only appear surrounded by letters or numbers" minlength="5" maxlength="15" placeholder="Username" required/>
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
    <img src="<?=getRootUrl()?>/images/cuteDoggos.jpeg" alt="Pet image"/>
</section>

<?php }

require_once(dirname(__FILE__) ."/../templates/common/footer.php"); ?>
