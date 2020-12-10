
<?php
function displayPetTile($image, $title, $description, $stats) { ?>
    <article class="tile">
        <div class="image" style="background: url('<?=$image?>'); background-size: cover; background-position: 50%;"></div>
        <header><h3><?=$title?></h3></header>
        <p><?=$description?></p>
        <footer><?=$stats?></footer>
    </article>
<?php }
function displayUserTile($image, $title, $description, $stats) { ?>
    <article class="tile">
        <div class="image" style="background: url('<?=$image?>'); background-size: cover; background-position: 50%;"></div>
        <header><h3><?=$title?></h3></header>
        <p><?=$description?></p>
        <footer><?=$stats?></footer>
    </article>
<?php } ?>
