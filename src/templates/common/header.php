<?php
require_once(dirname(__FILE__)."/../../control/api/user.php");
require_once(dirname(__FILE__)."/doc_header.php");
$section = isset($GLOBALS['section']) ? $GLOBALS['section'] : "home";
?>
<header>
    <a href="<?=getRootUrl()?>"><h1>To The Rescue!</h1></a>
    <label for="hamburguer">
        <i class="icofont-navigation-menu"></i> 
    </label>
    <input type="checkbox" id="hamburguer" />
    
    <nav id="mainMenu">
        <ul>
            <li><a <?=$section == 'home' ? 'class="active"' : ''?> href="<?=getRootUrl()?>">Home</a></li>
            <li><a <?=$section == 'discover' ? 'class="active"' : ''?> href="<?=getRootURL()?>/discover">Discover</a></li>
        </ul>
    </nav>
    <nav id="sessionMenu">
        <ul>
            <?php 
                $loggedInUser = Session\getAuthenticatedUser();
                if ($loggedInUser == false) { ?>
                    <li><a href="<?=getRootUrl()?>/signin">Sign In</a></li>
                    <li><a href="<?=getRootUrl()?>/signup" class="contrastButton">Sign Up</a></li>
                <?php } else { ?>
                    <li><a href="<?=getRootUrl()?>/action/signout">Sign Out</a></li>
                    <li><a href="<?=getRootUrl()?>/pets" class="contrastButton">My Pets</a></li>
                    <li><a href="<?=getRootUrl()?>/user/<?=htmlentities($loggedInUser['username'])?>" class="contrastButton">My Profile</a></li>
                <?php } ?>
        </ul>
    </nav>
</header>