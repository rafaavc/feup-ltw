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

const fileInputButtons = [{ obj: document.querySelector('input[type=file]:last-of-type'), id: 0 }];
fileInputButtons[0].obj.addEventListener('change', handleFileInput);
const photoContainer = document.querySelector('form[name=addPet] > div:last-of-type > div.photos');

function handleFileInput() {
    const lastButton = fileInputButtons[fileInputButtons.length-1].obj;
    const lastButtonId = fileInputButtons[fileInputButtons.length-1].id;
    const nextButton = document.createElement('input');
    nextButton.type = "file";
    nextButton.name = lastButton.name;
    nextButton.addEventListener('change', handleFileInput);

    lastButton.style.display = "none";
    
    lastButton.parentNode.insertBefore(nextButton, lastButton);

    fileInputButtons.push({ obj: nextButton, id: lastButtonId+1 });

    const file = this.files[0];
    const reader  = new FileReader();
    reader.onload = function(e)  {
        const imageWrapper = document.createElement('div');

        const image = document.createElement("img");
        image.src = e.target.result;
        imageWrapper.appendChild(image);

        const removeButton = document.createElement('div');
        removeButton.classList.add('remove');
        removeButton.addEventListener('click', function() {
            console.log(fileInputButtons, lastButtonId);
            const buttonIdx = fileInputButtons.findIndex((el) => el.id === lastButtonId);
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


const specieSelect = document.getElementById('specie');
specieSelect.addEventListener('change', updateRaceSelect);
updateRaceSelect.bind(specieSelect)();

const addSpeciesButton = document.getElementById('addSpeciesButton');
const addColorButton = document.getElementById('addColorButton');
const addRaceButton = document.getElementById('addRaceButton');
const addSizeButton = document.getElementById('addSizeButton');

function toggleAddingMode(e) {
    if (e != undefined) e.preventDefault();
    const entity = this.dataset.entity;
    if (this.dataset.savedValue != undefined && this.dataset.savedValue != "") { // creating
        this.innerHTML = this.dataset.savedValue;
        this.dataset.savedValue = '';

        const input = document.getElementById(`${entity}Input`);
        input.nextSibling.remove();
        input.remove();
    } else {   // opening
        this.dataset.savedValue = this.innerHTML;
        this.innerHTML = '';
        const icon = document.createElement('i');
        icon.classList.add("icofont-ui-close");

        this.appendChild(icon);
        this.appendChild(document.createTextNode('Cancel'));

        const input = document.createElement("input");
        input.type = "text";
        input.id = `${entity}Input`;
        input.placeholder = entity;

        const context = this;
        const button = document.createElement('button');
        button.classList.add('simpleButton');
        button.appendChild(document.createTextNode(`Add ${entity}`));
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const option = document.createElement('option');
            option.value = input.value;
            option.appendChild(document.createTextNode(input.value));
            option.selected = true;
            
            document.querySelector(`select[name=${entity.toLowerCase()}]`).appendChild(option);
            toggleAddingMode.bind(context)();
        });

        this.parentNode.parentNode.appendChild(input);
        this.parentNode.parentNode.appendChild(button);
    }
}

addSpeciesButton.addEventListener('click', toggleAddingMode);
addColorButton.addEventListener('click', toggleAddingMode);
addRaceButton.addEventListener('click', toggleAddingMode);
addSizeButton.addEventListener('click', toggleAddingMode);
