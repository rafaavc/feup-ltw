import { initWebsite } from './modules/init.js'
import { sendPostRequest, sendDeleteRequest } from './modules/ajax.js';
import { getCSRF } from './modules/utils.js';

initWebsite();

function adoptionReplyOnload(parent, check = true) {
    const res = JSON.parse(this.responseText);
    const container = parent.parentNode;
    if (res.value == true) {
        parent.remove();
    }

    if (check) checkEmpty(container);
}

function checkEmpty(container) {
    if (container.children.length == 1) {
        const pElem = document.createElement('p');
        pElem.appendChild(document.createTextNode('Your pets have no adoption proposals.'))
        container.appendChild(pElem);
    }
}

function acceptAdoption() {
    const petId = this.dataset.pet;
    const adopterId = this.dataset.adopter;
    const parent = this.parentNode.parentNode;
    const container = parent.parentNode;
    sendPostRequest(`api/adoption/${petId}`, { adopter: adopterId, csrf: getCSRF() }, function() { 
        adoptionReplyOnload.bind(this)(parent, false);
        if (!JSON.parse(this.responseText).value) return;
        const buttons = document.querySelectorAll('.petManagementAdoption > p > button.declineAdoption');
        for (const button of buttons) {
            if (button.dataset.pet == petId) button.parentNode.parentNode.remove();
        }
        checkEmpty(container);
        const petStates = document.querySelectorAll('.tagLabel');
        for (const state of petStates) {
            if (state.dataset.petId == petId) {
                state.classList.remove('ready');
                state.classList.add('adopted');
                state.innerHTML = 'Adopted';
            }
        }
    });
}

function declineAdoption() {
    const petId = this.dataset.pet;
    const adopterId = this.dataset.adopter;
    const parent = this.parentNode.parentNode;
    sendDeleteRequest(`api/adoption/${petId}/${adopterId}/${getCSRF()}`, function() { adoptionReplyOnload.bind(this)(parent) });
}


const acceptAdoptionButtons = document.getElementsByClassName('acceptAdoption');
const declineAdoptionButtons = document.getElementsByClassName('declineAdoption');

for (const b of acceptAdoptionButtons) b.addEventListener('click', acceptAdoption);
for (const b of declineAdoptionButtons) b.addEventListener('click', declineAdoption);
