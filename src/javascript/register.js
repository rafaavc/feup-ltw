import { sendGetRequest, sendSyncGetRequest } from './modules/ajax.js'
import './generic.js'

function onElementChange() {
    const elementInput = this;
    if (elementInput.value == "") {
        elementInput.previousSibling.remove();
        return;
    }
    sendGetRequest(`api/existence/${elementInput.name}/${elementInput.value}`, function() {
        const res = JSON.parse(this.responseText);
        const message = res.value ? `The ${elementInput.name} is already in use.` : `The ${elementInput.name} is not in use.`;
        const color = res.value ? "darkred" : "darkgreen";
        if (elementInput.previousSibling.tagName != 'P') {
            const el = document.createElement('p');
            el.innerHTML = message;
            el.style.fontSize = "0.8em";
            el.style.color = color;
            elementInput.parentNode.insertBefore(el, elementInput);
        } else {
            elementInput.previousSibling.innerHTML = message;
            elementInput.previousSibling.style.color = color;
        }
    });
}

const mail = document.getElementById('mail');
const username = document.getElementById('username');
const form = document.querySelector('section.authForm form');

const fileInputButton = document.getElementById('profilePhoto');
fileInputButton.addEventListener('change', handleFileInput);
fileInputButton.style.display = "none";
const profilePhotoButton = document.getElementById('profilePhotoButton');

form.addEventListener('submit', function(e) {
    let valid = false;
    sendSyncGetRequest(`api/existence/mail/${mail.value}`, function(req) {
        const res = JSON.parse(req.responseText);
        if (!res.value) valid = true;
    });
    if (!valid) {
        mail.focus();
        e.preventDefault();
        return;
    }
    valid = false;
    sendSyncGetRequest(`api/existence/username/${username.value}`, function(req) {
        const res = JSON.parse(req.responseText);
        console.log(res);
        if (!res.value) valid = true;
    });
    if (!valid) {
        username.focus();
        e.preventDefault();
        return;
    }
    if (!fileInputButton.value) {
        profilePhotoButton.focus();
        e.preventDefault();
        return;
    }
});


username.addEventListener('input', onElementChange);
mail.addEventListener('input', onElementChange);


profilePhotoButton.addEventListener('click', function(e) {
    e.preventDefault();
    fileInputButton.click();
});

const photoContainer = document.querySelector('form > div.photoContainer');
function handleFileInput() {
    const file = this.files[0];
    const reader  = new FileReader();
    reader.onload = function(e)  {
        const image = document.createElement("img");
        image.src = e.target.result;
        photoContainer.innerHTML = '';
        photoContainer.appendChild(image);
    }
    reader.readAsDataURL(file);
}

