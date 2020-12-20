import { sendPostRequest } from "./ajax.js";
import { getRootUrl } from './init.js';
import { getCSRF } from "./utils.js";
import { askForDeleteConfirm } from './pages/profile.js'

export function toggleAddingMode(e, onClickExtender, onRcvExtender) {
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
            
            const inputValue = input.value;
            const regExp = new RegExp(/^[a-zA-Z]+( [a-zA-Z]+)*$/);
            if (!regExp.test(inputValue)) {
                input.focus();
                const pWarning = document.createElement('p');
                pWarning.classList.add('notice');
                pWarning.style.marginTop = 0;
                pWarning.innerHTML = "May only have letters and spaces, and no more than one space in a row."
                window.setTimeout(() => { pWarning.remove() }, 3000);
                input.parentNode.appendChild(pWarning);
                return;
            }

            const option = document.createElement('option');
            option.value = inputValue;
            option.appendChild(document.createTextNode(input.value));
            option.selected = true;
            
            if (entity == 'List' && !createListRequest(entity, input, option, visibilitySelect, description, onRcvExtender)) 
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

    const pElem = document.createElement('p');
    pElem.appendChild(document.createTextNode('This list has no pets.'));
    pElem.style.marginTop = '0';
    petGridContent.appendChild(pElem);

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

function createListRequest(entity, input, option, visibilitySelect, description, onRcvExtender) {
    const lists = document.getElementById('lists');
    const form = document.getElementById(`${entity}Input`);
    if (input.value === "") {
        showUpdatedField("List cannot have empty title", form, true, "listTitle");
        return false;
    }
    if (input.value.length > 20) {
        showUpdatedField('Title needs to have between 1 and 20 characters', form, true, "listTile");
        return false;
    }

    option.selected = true;
    sendPostRequest(getRootUrl() + "/api/user", 
                    {title: input.value, visibility: visibilitySelect.selectedIndex, description: description.value, csrf: getCSRF()}, 
                    function() {
        const res = JSON.parse(this.responseText);
        option.value = res.id;
        const id = res.id;

        const list = document.createElement("div");
        list.dataset.id = id;
        list.className = "list";
        
        const descriptionBlock = document.createElement("p");
        descriptionBlock.innerHTML = description.value;
        descriptionBlock.style.marginTop = "0";
        list.appendChild(descriptionBlock);
        list.appendChild(createEmptyTileList(input.value, id));
        list.style.marginTop = "0";

        lists.appendChild(list);
        
        const p = lists.querySelector('#lists > p');
        if (p != null) p.remove();
        if (onRcvExtender != null) onRcvExtender();

        addDeleteButton();
        return true;
    });

    console.log(res);
    return res;
}

function addDeleteButton() {
    const listButtons = document.getElementById("listButtons");
    if (document.getElementById("removeListButton") == undefined){
        const deleteButton = document.createElement("button");
        deleteButton.className = "simpleButton";
        deleteButton.id = "removeListButton";
        deleteButton.dataset.entity = "List";
        deleteButton.innerHTML = "<i class='icofont-ui-delete'></i>Delete list";
        deleteButton.addEventListener('click', askForDeleteConfirm);

        listButtons.appendChild(deleteButton);
    }
}