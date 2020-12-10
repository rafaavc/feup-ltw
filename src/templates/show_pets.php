<?php
function showPetList($pets) {
?>
<div class="petGrid">
    <div class="arrow left"></div>
    <div class="petGridContent">
        <?php
        foreach($pets as $pet) {
        ?>
            <article>
                <div class="image" style="background-image: url(<?= '../images/petProfilePictures/' . $pet['id'] . '.jpg' ?>);"></div>
                <header>
                    <h4><?=htmlentities($pet['name'])?></h4>
                </header>
                <p><?=htmlentities($pet['description'])?></p>
                <footer>
                    <a class="simpleButton contrastButton" href="#">Meet <?=htmlentities($pet['name'])?></a>
                </footer>
            </article>
        <?php    
        }
        ?>
    </div>    
    <div class="arrow right"></div> 
</div>

<?php
}
?>