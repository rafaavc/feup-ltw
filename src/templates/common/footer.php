<footer>
    <div>
        <section class="footerPages">
            <h3>Pages</h3>
            <nav id="footerMainMenu">
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Pets</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Help</a></li>
                </ul>
            </nav>
        </section>
        <section id="footerContactUs">
            <h3>Contact us:</h3>
            <nav>
                <ul>
                    <li><a href="#">Invalidemail@nowhere.com</a></li>
                    <li><a href="#">912345678</a></li>
                    <li><a href="#">R. Dr. Roberto Frias, 4200-465 Porto</a></li>
                    <li><a href="#">Help</a></li>
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
                    <li><a href="<?=getRootUrl()?>/user/<?=$loggedInUser['username']?>">My Profile</a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>
    <p>To The Rescue &copy; 2020</p>
</footer>
<?php
include_once(dirname(__FILE__)."/doc_footer.php");
?>