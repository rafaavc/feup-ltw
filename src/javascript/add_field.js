import { sendPostRequest } from "./ajax.js";
import { getRootUrl } from './init.js';

export function toggleAddingMode(e, onClickExtender) {
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

        const visibilitySelect = document.createElement("select");
        const description = document.createElement("textarea");

        if (entity == 'List')
            listScenario.bind(this)(entity, input, visibilitySelect, description);
        else {
            input.id = `${entity}Input`;
            input.placeholder = entity;
            this.parentNode.parentNode.appendChild(input);
        }

        const context = this;
        const button = document.createElement('button');
        button.classList.add('simpleButton');
        button.classList.add('contrastButton');
        button.appendChild(document.createTextNode(`Add ${entity}`));
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const option = document.createElement('option');
            option.value = input.value;
            option.appendChild(document.createTextNode(input.value));
            option.selected = true;
            
            if (entity == 'List' && !createListRequest(entity, input, option, visibilitySelect, description)) 
                return;

            document.querySelector(`select[name=${entity.toLowerCase()}]`).appendChild(option);
            toggleAddingMode.bind(context)();
            if (onClickExtender != null) onClickExtender();

        });

        this.parentNode.parentNode.appendChild(button);
    }
}

function createEmptyTileList(title, id) {
    const mainDiv = document.createElement("div");
    mainDiv.name = title;
    mainDiv.className = "petGrid";
    mainDiv.dataset.id = id;

    const arrowLeft = document.createElement("div");
    arrowLeft.className = "arrow left";
    mainDiv.appendChild(arrowLeft);

    const petGridContent = document.createElement("div");
    petGridContent.className = "petGridContent";
    mainDiv.appendChild(petGridContent);

    const arrowRight = document.createElement("div");
    arrowRight.className = "arrow right";
    mainDiv.appendChild(arrowRight);

    return mainDiv;
}

export function showUpdatedField(message, block, errorMessage, param) {
    let p = block.getElementsByClassName(param)[0];
    if (p == undefined) {
        p = document.createElement('p');
        p.className = param;
    }

    p.innerHTML = message;
    p.style.fontSize = '0.8rem';
    p.style.color = errorMessage ? 'red' : 'green';
    block.appendChild(p);
    setTimeout(function () { p.remove() }, 3000);
}

function listScenario(entity, input, visibilitySelect, description) {
    const addListForm = document.createElement("form");
    addListForm.id = `${entity}Input`;

    input.id = "newListTitle";
    input.placeholder = "Title";
    addListForm.appendChild(input);

    const privateOption = document.createElement("option");
    privateOption.value = "Private";
    privateOption.innerHTML = "Private";
    visibilitySelect.appendChild(privateOption);

    const publicOption = document.createElement("option");
    publicOption.value = "Public";
    publicOption.innerHTML = "Public";
    visibilitySelect.appendChild(publicOption);

    addListForm.appendChild(visibilitySelect);

    description.id = "addDescription";
    description.placeholder = "Description";
    addListForm.appendChild(description);

    this.parentNode.parentNode.appendChild(addListForm);
}

function createListRequest(entity, input, option, visibilitySelect, description) {
    const lists = document.getElementById('lists');
    const form = document.getElementById(`${entity}Input`);
    if (input.value === "") {
        showUpdatedField("List cannot have empty title", form, true, "listTitle");
        return false;
    }

    option.selected = false;
    sendPostRequest(getRootUrl() + "/api/user", 
                    {title: input.value, visibility: visibilitySelect.selectedIndex, description: description.innerHTML}, 
                    function() {
        const res = JSON.parse(this.responseText);
        option.value = res.id;

        const id = res.id;
        lists.appendChild(createEmptyTileList(input.value, id));
        }
    );

    return true;
}