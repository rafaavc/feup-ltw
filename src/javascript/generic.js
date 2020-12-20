import { getRootUrl, initWebsite } from './modules/init.js'
import { sendGetRequest } from './modules/ajax.js'
import { createPetTile } from './modules/tile.js'

createPetsTileList();
initWebsite();

function createPetsTileList() {
    sendGetRequest(`${getRootUrl()}/api/pet/5`, function() {
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
