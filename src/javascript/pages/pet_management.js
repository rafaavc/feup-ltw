import { initWebsite } from '../init.js'
import { sendPostRequest, sendDeleteRequest } from '../ajax.js';

initWebsite();

function acceptAdoption() {
    const petId = this.dataset.pet;
    const adopterId = this.dataset.adopter;
    console.log('sending...')
    sendPostRequest(`api/adoption/${petId}`, { adopter: adopterId }, function() {});
}

function declineAdoption() {
    const petId = this.dataset.pet;
    const adopterId = this.dataset.adopter;
    sendDeleteRequest(`api/adoption/${petId}`, { adopter: adopterId }, function() {});    
}


const acceptAdoptionButtons = document.getElementsByClassName('acceptAdoption');
const declineAdoptionButtons = document.getElementsByClassName('declineAdoption');

for (const b of acceptAdoptionButtons) b.addEventListener('click', acceptAdoption);
for (const b of declineAdoptionButtons) b.addEventListener('click', declineAdoption);
