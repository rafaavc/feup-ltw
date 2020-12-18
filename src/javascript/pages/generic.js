import { getRootUrl, initWebsite } from '../init.js'
import { sendPostRequest } from '../ajax.js'
import { createPetTile } from '../tile.js'

createPetsTileList();
initWebsite();

function createPetsTileList() {
    sendPostRequest(getRootUrl() + "/api/pet", {size: 5}, function() {
        const res = JSON.parse(this.responseText);
        const pets = res.pets;

        const petGridContent = document.querySelector('#indexPets > .petGrid > .petGridContent');
        if (petGridContent == null) return;

        for (const pet of pets) {
            const tile = createPetTile(pet);
            petGridContent.appendChild(tile);
        }
    });
}
