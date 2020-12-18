<?php
$GLOBALS['section'] = 'signin';
include_once(dirname(__FILE__) ."/../templates/common/header.php");

if (Session\isAuthenticated()) { ?>
<section>
    <h3>You are already signed in.</h3>
</section>
<?php } else { ?>

<section class="authForm">
    <div>
        <h1>Sign In</h1>

        <?php 
            $message = Session\popMessage(); 
            if ($message != null) { ?>
                <p class="<?=$message['type']?>-message"><?=$message['content']?></p>
        <?php } ?>

        <form method="POST" action="<?=getRootURL()?>/control/actions/login.php">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>" />
            <div class="formField">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required/>
            </div>

            <div class="formField">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required/>
            </div>
            <input type="submit" value="Sign In" />
        </form>
    </div>
    <img src="<?=getRootUrl()?>/images/cuteDoggos.jpeg"/>
</section>

<?php }
include_once(dirname(__FILE__) ."/../templates/common/footer.php");

?>
