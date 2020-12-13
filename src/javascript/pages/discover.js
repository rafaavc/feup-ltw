import { sendGetRequest } from '../ajax.js'
import { createTile } from '../tile.js'
import { makeLoading } from '../loading.js'
import './generic.js'


const userSection = document.querySelector('#userResults > div');
const petSection = document.querySelector('#petResults > div');

function handleSearch(e) {
    if (e != null) e.preventDefault();

    const type = document.getElementById('type').value;
    if (type == -1) {
        makeLoading(userSection);
        makeLoading(petSection);
    } else if (type == 'user') {
        makeLoading(userSection);
    } else if (type == 'pet') {
        makeLoading(petSection);
    }

    const species = document.getElementById('species').value;
    const search = document.getElementById('search').value;

    sendGetRequest("api/search", [type, species, search], function() {
        const res = JSON.parse(this.responseText);
        if (res.users != undefined) updateUsers(res.users);
        if (res.pets != undefined) updatePets(res.pets);
    });
}

function updateUsers(users) {
    userSection.innerHTML = "";
    for (const user of users) {
        const footer = document.createTextNode(user.petCount + ' pets');
        const tile = createTile(`user/${user.username}`, `images/userProfilePictures/${user.id}.jpg`, user.name, footer, user.description);

        userSection.appendChild(tile);
    }
}
function updatePets(pets) {
    petSection.innerHTML = "";
    for (const pet of pets) {
        const tile = createTile(`pet/${pet.id}`, `images/petProfilePictures/${pet.id}.jpg`, pet.name, null, pet.description);

        petSection.appendChild(tile);
    }
}

document.querySelector('.searchForm > form').addEventListener('submit', (e) => e.preventDefault());
document.getElementById('search').addEventListener('input', handleSearch);
document.getElementById('type').addEventListener('change', handleSearch);
document.getElementById('species').addEventListener('change', handleSearch);

handleSearch();

