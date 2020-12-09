<section class="profile_header">
    <img src="<?=getRootURL()?>/images/cuteDoggos.jpeg" />
    <div>
        <header>
            <section class="textButtonPair">
                <h2>Name</h2>
                <input type="checkbox" class="edit" id="nameEdit"/> 
                <label class="edit" for="nameEdit"></label>
            </section>
            <section class="textButtonPair">
                <h4>@username</h4> <!-- remove header-->
                <input type="checkbox" class="edit" id="usernameEdit"/> 
                <label class="edit" for="usernameEdit"></label>
            </section>
            <section class="textButtonPair">
                <h4>mail</h4> <!-- remove header-->
                <input type="checkbox" class="edit" id="mailEdit"/> 
                <label class="edit" for="mailEdit"></label>
            </section>
        </header>
        <div class="textButtonPair" id="bio">
            <p>Et has minim elitr intellegat. Mea aeterno eleifend antiopam ad, nam no suscipit quaerendum. At nam minimum ponderum. Est audiam animal molestiae te. Ex duo eripuit mentitum.</p>
            <input type="checkbox" class="edit" id="bioEdit"> 
            <label class="edit" for="nameEdit"></label>
        </div>
    </div>
    <aside>
        <label for="editProfile" id="editProfileLabel">
            <a class="simpleButton contrastButton">Edit Profile</a>
        </label>
        <input type="checkbox" id="editProfile" onclick="editOptions()"/> 
    </aside>
</section>