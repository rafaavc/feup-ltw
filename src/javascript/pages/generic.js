import { getRootUrl, initWebsite } from '../init.js'
import { sendPostRequest } from '../ajax.js'
import { createTile } from '../tile.js'

createPetsTileList();
initWebsite();

function createPetsTileList() {
    sendPostRequest(getRootUrl() + "/api/pet", {size: 5}, function() {
        const res = JSON.parse(this.responseText);
        const pets = res.pets;

        const petGridContent = document.querySelector('#indexPets > .petGrid > .petGridContent');

        for (const pet of pets) {
            const tile = createTile(`pet/${pet.id}`, `images/petProfilePictures/${pet.id}.jpg`, pet.name, null, pet.description, null, false);
            petGridContent.appendChild(tile);
        }
    });
}
