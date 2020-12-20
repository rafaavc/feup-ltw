<footer>
    <div>
        <section class="footerPages">
            <h3>Pages</h3>
            <nav id="footerMainMenu">
                <ul>
                    <li><a href="<?=getRootUrl()?>">Home</a></li>
                    <li><a href="<?=getRootUrl()?>/discover">Discover</a></li>
                </ul>
            </nav>
        </section>
        <section id="footerContactUs">
            <h3>Contact us:</h3>
            <nav>
                <ul>
                    <li>invalidemail@totherescue.com</li>
                    <li>912345678</li>
                    <li>R. Dr. Roberto Frias, 4200-465 Porto</li>
                </ul>
            </nav>
        </section>
        <nav id="footerSessionMenu">
            <ul>
                <?php if ($loggedInUser == false) { ?>
                    <li><a href="<?=getRootUrl()?>/signin">Sign In</a></li>
                    <li><a href="<?=getRootUrl()?>/signup">Sign Up</a></li>
                <?php } else { ?>
                    <li><a href="<?=getRootUrl()?>/action/signout">Sign Out</a></li>
                    <li><a href="<?=getRootUrl()?>/pets">My Pets</a></li>
                    <li><a href="<?=getRootUrl()?>/user/<?=htmlentities($loggedInUser['username'])?>">My Profile</a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>
    <p>To The Rescue &copy; 2020</p>
</footer>
<?php
require_once(dirname(__FILE__)."/doc_footer.php");
?>