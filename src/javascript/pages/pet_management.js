import { initWebsite } from '../init.js'
import { sendPostRequest, sendDeleteRequest } from '../ajax.js';
import { getCSRF } from '../utils.js';

initWebsite();

function adoptionReplyOnload(parent) {
    const res = JSON.parse(this.responseText);
    const container = parent.parentNode;
    if (res.value == true) {
        parent.remove();
    }
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
    sendPostRequest(`api/adoption/${petId}`, { adopter: adopterId, csrf: getCSRF() }, function() { adoptionReplyOnload.bind(this)(parent) });
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
