<?php
$GLOBALS['section'] = 'signin';
include_once(dirname(__FILE__) ."/../templates/common/header.php");
?>

<section class="authForm">
    <h1>Sign In</h1>

    <?php 
        $message = Session\popMessage(); 
        if ($message != null) { ?>
            <p class="<?=$message['type']?>-message"><?=$message['content']?></p>
    <?php } ?>
    
    <form method="POST" action="<?=getRootURL()?>/control/actions/login.php">
        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>" />
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" required/>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" required/>

        <input type="submit" value="Sign In" />
    </form>
</section>

<?php
include_once(dirname(__FILE__) ."/../templates/common/footer.php");

?>
