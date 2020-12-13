import { sendGetRequest } from '../ajax.js';
import { getRootUrl } from '../init.js';
import './generic.js'

const raceSelect = document.getElementById('race');

function updateRaceSelect() {
    const specieId = this.value;
    sendGetRequest(getRootUrl() + "/api/pet", ["races", specieId], function() {
        raceSelect.innerHTML = '';
        const res = JSON.parse(this.responseText);

        const optElem = document.createElement('option');
        optElem.appendChild(document.createTextNode("- Select a Race -"));
        optElem.value = -1;
        raceSelect.appendChild(optElem);

        for (const race of res.races) {
            const optElem = document.createElement('option');
            optElem.value = race.id;
            optElem.appendChild(document.createTextNode(race.name));
            raceSelect.appendChild(optElem);
        }
    })
}

const specieSelect = document.getElementById('specie');
specieSelect.addEventListener('change', updateRaceSelect);
updateRaceSelect.bind(specieSelect)();
