<?php

include_once(dirname(__FILE__) ."/../templates/common/header.php");
?>

<section class="authForm">
    <h1>Sign Up</h1>

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
        <input type="email" id="mail" name="mail" placeholder="E-Mail" oninput="onElementChange('mail')" required/>

        <label for="description">Bio</label>
        <textarea placeholder="Bio" name="description" id="description"></textarea>

        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" oninput="onElementChange('username')" required/>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" required/>

        <input type="submit" value="Sign Up" />
    </form>
</section>

<?php
include_once(dirname(__FILE__) ."/../templates/common/footer.php");

?>

<script>
function onElementChange(element) {
    const elementInput = document.getElementById(element);
    if (elementInput.value == "") {
        elementInput.previousSibling.remove();
        return;
    }
    sendGetRequest("api/existence", [element, elementInput.value], function() {
        const res = JSON.parse(this.responseText);
        const message = res.value ? `The ${element} is already in use.` : `The ${element} is not in use.`;
        const color = res.value ? "darkred" : "darkgreen";
        if (elementInput.previousSibling.tagName != 'P') {
            let el = document.createElement('p');
            el.innerHTML = message;
            el.style.fontSize = "0.8em";
            el.style.color = color;
            elementInput.parentNode.insertBefore(el, elementInput);
        } else {
            elementInput.previousSibling.innerHTML = message;
            elementInput.previousSibling.style.color = color;
        }
    });
}
</script>