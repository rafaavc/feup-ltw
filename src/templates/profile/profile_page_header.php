<section class="profile_header">
    <img src="<?=getRootURL()?>/images/cuteDoggos.jpeg" />
    <div>
        <header>
            <section class="textButtonPair">
                <div id="name">
                    <h2>Name</h2>
                    <input type="button" class="edit" id="nameEdit" onclick="showField('nameForm', 'name')"/> 
                    <label class="edit name" for="nameEdit"></label>
                </div>
                <form id="nameForm">
                    <input type="text" />
                    <input type="submit" class="confirm" name="nameConfirm"/> 
                    <label for="nameConfirm"></label>
                    <input type="button" class="close" name="nameClose" onclick="resetSelection('nameForm', 'name')"/> 
                    <label for="nameClose"></label>
                </form>
            </section>
            <section class="textButtonPair">
                <div id="username">
                    <strong>@username</strong>
                    <input type="button" class="edit" id="usernameEdit" onclick="showField('usernameForm', 'username')"/> 
                    <label class="edit" for="usernameEdit"></label>
                </div>
                <form id="usernameForm">
                    <input type="text" />
                    <input type="submit" class="confirm" name="usernameConfirm"/> 
                    <label for="usernameConfirm"></label>
                    <input type="button" class="close" name="usernameClose" onclick="resetSelection('usernameForm', 'username')"/> 
                    <label for="usernameClose"></label>
                </form>
            </section>
            <section class="textButtonPair">
                <div id="mail">
                    <strong>mail</strong>
                    <input type="button" class="edit" id="mailEdit" onclick="showField('mailForm', 'mail')"/> 
                    <label class="edit" for="mailEdit"></label>
                </div>
                <form id="mailForm">
                    <input type="text" />
                    <input type="submit" class="confirm" name="mailConfirm"/> 
                    <label for="mailConfirm"></label>
                    <input type="button" class="close" name="mailClose" onclick="resetSelection('mailForm', 'mail')"/> 
                    <label for="mailClose"></label>
                </form>
            </section>
        </header>
        <section class="textButtonPair" id="bioSection">
            <div id="bio">
                <p>Et has minim elitr intellegat. Mea aeterno eleifend antiopam ad, nam no suscipit quaerendum. At nam minimum ponderum. Est audiam animal molestiae te. Ex duo eripuit mentitum.</p>
                <input type="button" class="edit" id="bioEdit" onclick="showField('bioForm', 'bio')"/> 
                <label class="edit" for="bioEdit"></label>
            </div>
            <form id="bioForm">
                <input type="submit" class="confirm" name="bioConfirm"/> 
                <label for="bioConfirm"></label>
                <input type="button" class="close" onclick="resetSelection('bioForm', 'bio')"/> 
                <label for="bioClose"></label>
            </form>
        </section>
    </div>
    <aside>
        <label for="editProfile" id="editProfileLabel">
            <a class="simpleButton contrastButton">Edit Profile</a>
        </label>
        <input type="checkbox" id="editProfile" onclick="editProfile()"/> 
    </aside>
</section>