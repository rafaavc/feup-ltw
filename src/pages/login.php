<?php

include_once(dirname(__FILE__) ."/../templates/common/header.php");
?>

<section class="authForm">
    <p><?php $message = Session\popMessage(); echo isset($message['content']) ? $message['content'] : ""?></p>
    <form method="POST" action="<?=getRootURL()?>/control/actions/login.php">
        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>" />
        <label>
            Username
            <input type="text" name="username" placeholder="username" />
        </label>
        <label>
            Password
            <input type="password" name="password" placeholder="password" />
        </label>
            <input type="submit" value="login" />
    </form>
</section>

<?php
include_once(dirname(__FILE__) ."/../templates/common/footer.php");

?>
