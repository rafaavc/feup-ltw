<?php

include_once(dirname(__FILE__) ."/../templates/common/header.php");
?>

<section class="authForm">
    <h1>Register</h1>

    <?php 
        $message = Session\popMessage(); 
        if ($message != null) { ?>
            <p class="<?=$message['type']?>-message"><?=$message['content']?></p>
    <?php } ?>
    
    <form method="POST" action="<?=getRootURL()?>/control/actions/register.php">
        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>" />

        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Full Name" required/>

        <label for="birthdate">Birthdate</label>
        <input type="text" id="birthdate" name="birthdate" placeholder="Birthdate" onfocus="(this.type = 'date')" required/>

        <label for="mail">E-Mail</label>
        <input type="email" id="mail" name="mail" placeholder="E-Mail" required/>

        <label for="description">Bio</label>
        <textarea placeholder="Bio" name="description" id="description"></textarea>

        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" required/>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" required/>

        <input type="submit" value="Sign Up" />
    </form>
</section>

<?php
include_once(dirname(__FILE__) ."/../templates/common/footer.php");

?>