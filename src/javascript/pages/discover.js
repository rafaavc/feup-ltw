import { sendGetRequest } from '../ajax.js'
import { createTile } from '../tile.js'
import { makeLoading } from '../loading.js'
import './generic.js'


const userResults = document.getElementById('userResults');
userResults.appendChild(document.createElement('div'));
const petResults = document.getElementById('petResults');
petResults.appendChild(document.createElement('div'));
const userSection = document.querySelector('#userResults > div');
const petSection = document.querySelector('#petResults > div');

const typeElem = document.getElementById('type');
const colorElem = document.getElementById('colors');
const sizeElem = document.getElementById('sizes');
const speciesElem = document.getElementById('species');
const searchElem = document.getElementById('search');

function handleSearch(e) {
    if (e != null) e.preventDefault();

    const type = typeElem.value;
    if (type == -1) {
        userResults.style.display = "block";
        petResults.style.display = "block";
        /*makeLoading(userSection);
        makeLoading(petSection);*/
    } else if (type == 'user') {
        userResults.style.display = "block";
        petResults.style.display = "none";
        //makeLoading(userSection);
    } else if (type == 'pet') {
        userResults.style.display = "none";
        petResults.style.display = "block";
        //makeLoading(petSection);
    }

    const species = speciesElem.value;
    const color = colorElem.value;
    const size = sizeElem.value;
    const search = searchElem.value;

    sendGetRequest(`api/search/${type}/${species}/${color}/${size}/${search}`, function() {
        const res = JSON.parse(this.responseText);
        if (res.users != undefined) updateUsers(res.users);
        if (res.pets != undefined) updatePets(res.pets);
    });
}

function updateUsers(users) {
    userSection.innerHTML = "";
    if (users.length == 0) {
        const pElem = document.createElement('p');
        pElem.appendChild(document.createTextNode('No users were found.'));
        userSection.appendChild(pElem);
    } else {
        for (const user of users) {
            const footer = document.createTextNode(user.petCount + ' pets');
            const tile = createTile(`user/${user.username}`, `images/userProfilePictures/${user.id}.jpg`, user.name, footer, user.description, null, false);

            userSection.appendChild(tile);
        }
    }
}
function updatePets(pets) {
    petSection.innerHTML = "";
    if (pets.length == 0) {
        const pElem = document.createElement('p');
        pElem.appendChild(document.createTextNode('No pets were found.'));
        petSection.appendChild(pElem);
    } else {
        for (const pet of pets) {
            const tile = createTile(`pet/${pet.id}`, `images/petProfilePictures/${pet.id}.jpg`, pet.name, null, pet.description, null, false);
            petSection.appendChild(tile);
        }
    }
}

document.querySelector('.searchForm > form').addEventListener('submit', (e) => e.preventDefault());
searchElem.addEventListener('input', handleSearch);
typeElem.addEventListener('change', handleSearch);
speciesElem.addEventListener('change', handleSearch);
colorElem.addEventListener('change', handleSearch);
sizeElem.addEventListener('change', handleSearch);

handleSearch();

