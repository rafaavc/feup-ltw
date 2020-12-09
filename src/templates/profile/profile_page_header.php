<section class="profile_header">
    <img src="<?=getRootURL()?>/images/cuteDoggos.jpeg" />
    <div>
        <header>
            <section class="textButtonPair">
                <h2>Name</h2>
                <input type="button" class="edit" id="nameEdit" onclick="showField('nameForm', 'nameEdit')"/> 
                <label class="edit name" for="nameEdit"></label>
                <form id="nameForm">
                    <input type="submit" class="confirm" name="nameConfirm"/> 
                    <label for="nameConfirm"></label>
                    <input type="button" class="close" name="nameClose" onclick="resetSelection()"/> 
                    <label for="nameClose"></label>
                </form>
            </section>
            <section class="textButtonPair">
                <h4>@username</h4> <!-- remove header-->
                <input type="button" class="edit" id="usernameEdit" onclick="showField('usernameForm', 'usernameEdit')"/> 
                <label class="edit" for="usernameEdit"></label>
                <form id="usernameForm">
                    <input type="submit" class="confirm" name="usernameConfirm"/> 
                    <label for="usernameConfirm"></label>
                    <input type="button" class="close" name="usernameClose" onclick="resetSelection()"/> 
                    <label for="usernameClose"></label>
                </form>
            </section>
            <section class="textButtonPair">
                <h4>mail</h4> <!-- remove header-->
                <input type="button" class="edit" id="mailEdit" onclick="showField('mailForm', 'mailEdit')"/> 
                <label class="edit" for="mailEdit"></label>
                <form id="mailForm">
                    <input type="submit" class="confirm" name="mailConfirm"/> 
                    <label for="mailConfirm"></label>
                    <input type="button" class="close" name="mailClose" onclick="resetSelection()"/> 
                    <label for="mailClose"></label>
                </form>
            </section>
        </header>
        <div class="textButtonPair" id="bio">
            <p>Et has minim elitr intellegat. Mea aeterno eleifend antiopam ad, nam no suscipit quaerendum. At nam minimum ponderum. Est audiam animal molestiae te. Ex duo eripuit mentitum.</p>
            <input type="button" class="edit" id="bioEdit" onclick="showField('bioForm', 'bioEdit')"/> 
            <label class="edit" for="bioEdit"></label>
            <form id="bioForm">
                <input type="submit" class="confirm" name="bioConfirm"/> 
                <label for="bioConfirm"></label>
                <input type="button" class="close" onclick="resetSelection()"/> 
                <label for="bioClose"></label>
            </form>
        </div>
    </div>
    <aside>
        <label for="editProfile" id="editProfileLabel">
            <a class="simpleButton contrastButton">Edit Profile</a>
        </label>
        <input type="checkbox" id="editProfile" onclick="editProfile()"/> 
    </aside>
</section>