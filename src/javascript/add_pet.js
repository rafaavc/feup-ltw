import { sendGetRequest } from './modules/ajax.js';
import { getRootUrl } from './modules/init.js';
import { toggleAddingMode } from './modules/add_field.js';
import './generic.js'

const raceSelect = document.getElementById('race');

function updateRaceSelect() {
    const specieId = this.value;
    sendGetRequest(`${getRootUrl()}/api/pet/races/${specieId}`, function() {
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
    });
}

const form = document.querySelector("form[name=addPet]");
const profilePhotoInput = document.querySelector('input[type=hidden][name=profilePhoto]');
const fileInputButtons = [{ obj: document.querySelector('input[type=file]:last-of-type'), id: 0 }];
fileInputButtons[0].obj.addEventListener('change', handleFileInput);
fileInputButtons[0].obj.style.display = "none";
const photoContainer = document.querySelector('form[name=addPet] > div:last-of-type > div.photos');
const addPhotoButton = document.getElementById('addPhotoButton');
addPhotoButton.addEventListener('click', function(e) {
    e.preventDefault();
    fileInputButtons[fileInputButtons.length-1].obj.click();
});

let showedProfilePhotoError = false;

const specieSelect = document.getElementById('specie');
const sizeSelect = document.getElementById('size');
const colorSelect = document.getElementById('color');

form.addEventListener('submit', function(e) {
    if (specieSelect.value == "-1") {
        specieSelect.focus();
        e.preventDefault();
        return;
    }
    if (sizeSelect.value == "-1") {
        sizeSelect.focus();
        e.preventDefault();
        return;
    }
    if (colorSelect.value == "-1") {
        colorSelect.focus();
        e.preventDefault();
        return;
    }
    if (profilePhotoInput.value == "") { // no profile photo has been selected
        e.preventDefault();
        addPhotoButton.focus();
        if (!showedProfilePhotoError) {
            const pElem = document.createElement('p');
            pElem.classList.add('error-message');
            pElem.appendChild(document.createTextNode('Please select a profile image.'));
            addPhotoButton.parentNode.insertBefore(pElem, addPhotoButton.nextSibling);
            showedProfilePhotoError = true;
        }
        return;
    }
});

function updateProfilePic() {
    const prevProfileImage = document.querySelectorAll('img.profilePicture');
    prevProfileImage.forEach((pi) => pi.classList.remove('profilePicture'));
    this.classList.add('profilePicture');

    const buttonId = parseInt(this.dataset.buttonId);
    const button = fileInputButtons.find((button) => button.id === buttonId).obj;
    profilePhotoInput.value = button.files[0].name;
}

function handleFileInput() {
    const lastButton = fileInputButtons[fileInputButtons.length-1].obj;
    const lastButtonId = fileInputButtons[fileInputButtons.length-1].id;
    const nextButton = document.createElement('input');
    nextButton.type = "file";
    nextButton.name = lastButton.name;
    nextButton.addEventListener('change', handleFileInput);
    nextButton.style.display = "none";
    nextButton.accept ="image/jpeg";

    lastButton.style.display = "none";
    
    lastButton.parentNode.insertBefore(nextButton, lastButton);

    fileInputButtons.push({ obj: nextButton, id: lastButtonId+1 });

    const file = this.files[0];
    const reader  = new FileReader();
    reader.onload = function(e)  {
        const imageWrapper = document.createElement('div');

        const image = document.createElement("img");
        image.src = e.target.result;
        image.addEventListener('click', updateProfilePic);
        image.dataset.buttonId = lastButtonId;
        imageWrapper.appendChild(image);

        const removeButton = document.createElement('div');
        removeButton.classList.add('remove');
        removeButton.addEventListener('click', function() {
            const buttonIdx = fileInputButtons.findIndex((el) => el.id === lastButtonId);
            if (profilePhotoInput.value === lastButton.files[0].name) {
                profilePhotoInput.value = '';
            }
            fileInputButtons[buttonIdx].obj.remove();
            this.parentNode.remove();
            
            fileInputButtons.splice(buttonIdx, 1);
        });
        const icon = document.createElement('i');
        icon.classList.add('icofont-ui-close');
        removeButton.appendChild(icon);
        imageWrapper.appendChild(removeButton);

        photoContainer.appendChild(imageWrapper);
    }
    reader.readAsDataURL(file);
}


specieSelect.addEventListener('change', updateRaceSelect);
updateRaceSelect.bind(specieSelect)();

const addSpeciesButton = document.getElementById('addSpeciesButton');
const addColorButton = document.getElementById('addColorButton');
const addRaceButton = document.getElementById('addRaceButton');
const addSizeButton = document.getElementById('addSizeButton');

addSpeciesButton.addEventListener('click', function(e) {
    toggleAddingMode.bind(this)(e, () => {
        updateRaceSelect.bind(specieSelect)();
    });
});
addColorButton.addEventListener('click', toggleAddingMode);
addRaceButton.addEventListener('click', toggleAddingMode);
addSizeButton.addEventListener('click', toggleAddingMode);
