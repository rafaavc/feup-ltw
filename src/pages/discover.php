<?php
$GLOBALS['section'] = 'discover';
require_once(dirname(__FILE__)."/../control/api/pet.php");
require_once(dirname(__FILE__)."/../control/api/user.php");
$pets = API\getArrayFromSTMT(API\getPets(), 10);
$users = API\getArrayFromSTMT(API\getUsers(), 10);
include_once(dirname(__FILE__) ."/../templates/common/header.php");
include_once(dirname(__FILE__) ."/../templates/tiles.php");
?>

<?php require_once(dirname(__FILE__)."/../templates/search_form.php"); ?>

<section id="petResults">
    <header><h2>Our Pets</h2></header>
    <div>
    <?php foreach($pets as $pet) { 
        displayPetTile(getRootUrl().'/images/petProfilePictures/'.$pet['id'].'.jpg', $pet['name'], $pet['description'], "no stats");
    } ?>
    </div>
</section>

<section id="userResults">
    <header><h2>Our Users</h2></header>
    <div>
    <?php foreach($users as $user) {
        displayUserTile(getRootUrl().'/images/userProfilePictures/'.$user['id'].'.jpg', $user['name'], $user['description'], $user['petCount']." <i class='icofont-cat-dog'></i>");
    } ?>
    </div>
</section>
<script>

function updateUsers(users) {
    const userSection = document.querySelector('#userResults > div');
    userSection.innerHTML = "";
    for (user of users) {
        userSection.innerHTML += <?='`'?><?php displayUserTile(getRootUrl().'/images/userProfilePictures/${user.id}.jpg', '${user.name}', '${user.description==null ? "" : user.description}', '${user.petCount}'." <i class='icofont-cat-dog'></i>")?><?='`'?>;
    }
}
function updatePets(pets) {
    const petSection = document.querySelector('#petResults > div');
    petSection.innerHTML = "";
    for (pet of pets) {
        petSection.innerHTML += <?='`'?><?php displayPetTile(getRootUrl().'/images/petProfilePictures/${pet.id}.jpg', '${pet.name}', '${pet.description}', "no stats")?><?='`'?>;
    }
}

function handleSearch() {
    sendGetRequest("api/search", [document.getElementById('type').value, document.getElementById('species').value, document.getElementById('search').value], function() {
        const res = JSON.parse(this.responseText);
            console.log(res);
        if (res.users != undefined) updateUsers(res.users);
        if (res.pets != undefined) updatePets(res.pets);
    });
}
</script>

<?php
include_once(dirname(__FILE__) ."/../templates/common/footer.php");
?>


