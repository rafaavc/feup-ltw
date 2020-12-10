
<?php
function displayPetTile($url, $image, $title, $description, $stats) { ?>
    <a href="<?=$url?>">
        <article class="tile">
            <div class="image" style="background: url('<?=$image?>'); background-size: cover; background-position: 50%;"></div>
            <header><h3><?=$title?></h3></header>
            <p><?=$description?></p>
            <footer><?=$stats?></footer>
        </article>
    </a>
<?php }
function displayUserTile($url, $image, $title, $description, $stats) { ?>
    <a href="<?=$url?>">
        <article class="tile">
            <div class="image" style="background: url('<?=$image?>'); background-size: cover; background-position: 50%;"></div>
            <header><h3><?=$title?></h3></header>
            <p><?=$description?></p>
            <footer><?=$stats?></footer>
        </article>
    </a>
<?php } ?>
