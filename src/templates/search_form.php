<?php $species = API\getArrayFromSTMT(API\getSpecies(), true); ?>
<section class="searchForm">
    <form onsubmit="handleSearch()">
        <label for="search">Search</label>
        <input type="text" id="search" name="search" placeholder="Search" oninput = handleSearch() />

        <label for="type">Species</label>
        <select name="type" id="type">
            <option value="-1">Pet / User</option>
            <option value="pet">Pet</option>
            <option value="user">User</option>
        </select>

        <label for="species">Species</label>
        <select name="species" id="species">
            <option value="-1">All Species</option>
            <?php foreach($species as $specie) { ?>
                <option value="<?=$specie['id']?>"><?=$specie['name']?></option>
            <?php } ?>
        </select>
    </form>
</section>


