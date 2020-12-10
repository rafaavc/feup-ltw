<section class="profile_header">
    <img src="<?=getRootURL()?>/images/cuteDoggos.jpeg" />
    <div>
        <header>
            <section class="textButtonPair">
                <div id="name">
                    <h2>Name</h2>
                    <button class="edit" id="nameEdit" onclick="showField('nameForm', 'name')"><i class="icofont-ui-edit"></i></button> 
                </div>
                <form id="nameForm">
                    <input type="text" />
                    <button class="confirm" name="nameConfirm"><i class="icofont-ui-check"></i></button>
                    <button class="close" name="nameClose" onclick="resetSelection('nameForm', 'name')"><i class="icofont-ui-close"></i></button>
                </form>
            </section>
            <section class="textButtonPair">
                <div id="username">
                    <strong>@username</strong>
                    <button class="edit" id="usernameEdit" onclick="showField('usernameForm', 'username')"><i class="icofont-ui-edit"></i></button>
                </div>
                <form id="usernameForm">
                    <input type="text" />
                    <button class="confirm" name="usernameConfirm"/><i class="icofont-ui-check"></i></button> 
                    <button class="close" name="usernameClose" onclick="resetSelection('usernameForm', 'username')"><i class="icofont-ui-close"></i></button>
                </form>
            </section>
            <section class="textButtonPair">
                <div id="mail">
                    <strong>mail</strong>
                    <button class="edit" id="mailEdit" onclick="showField('mailForm', 'mail')"><i class="icofont-ui-edit"></i></button>
                </div>
                <form id="mailForm">
                    <input type="text" />
                    <button class="confirm" name="mailConfirm"/><i class="icofont-ui-check"></i></button>
                    <button class="close" name="mailClose" onclick="resetSelection('mailForm', 'mail')"><i class="icofont-ui-close"></i></button>
                </form>
            </section>
        </header>
        <section class="textButtonPair" id="bioSection">
            <div id="bio">
                <p>Et has minim elitr intellegat. Mea aeterno eleifend antiopam ad, nam no suscipit quaerendum. At nam minimum ponderum. Est audiam animal molestiae te. Ex duo eripuit mentitum.</p>
                <button class="edit" id="bioEdit" onclick="showField('bioForm', 'bio')"><i class="icofont-ui-edit"></i></button>
            </div>
            <form id="bioForm">
                <input type="text" />
                <button class="confirm" name="bioConfirm"><i class="icofont-ui-check"></i></button> 
                <button class="close" onclick="resetSelection('bioForm', 'bio')"><i class="icofont-ui-close"></i></button>
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