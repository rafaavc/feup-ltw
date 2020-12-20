<?php 

$species = API\getArrayFromSTMT(API\getSpecies(), true); 
$colors = API\getArrayFromSTMT(API\getColors(), true); 
$sizes = API\getArrayFromSTMT(API\getSizes(), true); 

?>
<section class="searchForm">
    <form>
        <label for="search">Search</label>
        <input type="text" id="search" name="search" placeholder="Search" />

        <label for="type">Type</label>
        <select name="type" id="type">
            <option value="-1">Pet / User</option>
            <option value="pet">Pet</option>
            <option value="user">User</option>
        </select>

        <label for="species">Species</label>
        <select name="species" id="species">
            <option value="-1">All Species</option>
            <?php foreach($species as $specie) { ?>
                <option value="<?=$specie['id']?>"><?=htmlentities($specie['name'])?></option>
            <?php } ?>
        </select>

        <label for="colors">Colors</label>
        <select name="colors" id="colors">
            <option value="-1">All Colors</option>
            <?php foreach($colors as $color) { ?>
                <option value="<?=$color['id']?>"><?=htmlentities($color['name'])?></option>
            <?php } ?>
        </select>

        <label for="sizes">Sizes</label>
        <select name="sizes" id="sizes">
            <option value="-1">All Sizes</option>
            <?php foreach($sizes as $size) { ?>
                <option value="<?=$size['id']?>"><?=htmlentities($size['name'])?></option>
            <?php } ?>
        </select>
    </form>
</section>


