import { getRootUrl, initWebsite } from '../init.js'
import { sendPostRequest } from '../ajax.js'
import { createTile } from '../tile.js'
//import { toggleAddingMode } from './add_pet.js'

const editProfileButton = document.getElementById("editProfile");
if (editProfileButton != null)
    editProfileButton.addEventListener('click', editProfile);

const forms = Array.from(document.getElementsByClassName('textButtonPair'));
forms.forEach(form => {
    const inputField = form.children[0];
    const editForm = form.children[1];
    
    const edit = inputField.getElementsByClassName("edit");
    edit[0].addEventListener('click', () => {
        showSelection(editForm, inputField);
    });

    const confirm = editForm.getElementsByClassName("confirm");
    confirm[0].addEventListener('click', (event) => {
        event.preventDefault();
        confirmSelection(editForm, inputField);
    });

    const close = editForm.getElementsByClassName("close");
    close[0].addEventListener('click', (event) => {
        event.preventDefault();
        resetSelection(editForm, inputField);
    });
    
});

const lists = document.getElementById('lists');
const listSelect = document.getElementById('list-select');
listSelect.addEventListener('change', () => {
    for (let i = 0; i < lists.children.length; i++) {
        console.log(lists.children[i]);
        if (i == listSelect.options.selectedIndex)
            lists.children[i].style.display = 'grid';
        else lists.children[i].style.display = 'none';
    }
});

createTileLists();

initWebsite();

function editProfile() {
    const editProfileLabel = document.querySelector("#editProfileLabel > a");
    const editProfile = document.getElementById("editProfile");
    const forms = document.querySelectorAll(".textButtonPair form");
    const editFields = document.querySelectorAll(".textButtonPair .edit");
    const initialFields = document.querySelectorAll(".textButtonPair > div");

    if (editProfile.checked) {
        editProfileLabel.innerHTML = "Close edition";
        editFields.forEach(field => field.style.display = "flex");
    }
    else {
        editProfileLabel.innerHTML = "Edit profile";
        editFields.forEach(field => field.style.display = "none");
        forms.forEach(form => form.style.display = "none");
        initialFields.forEach(field => field.style.display = "flex");
    }
}

function showSelection(editForm, inputField) {
    editForm.style.display = "flex";
    inputField.style.display = "none";

    let formText;
    let formValue = inputField.children[0].innerHTML;
    //if (inputField.id == "username") formValue = formValue.substr(1);

    if (inputField.id == "bio")
        formText = editForm.querySelector("textarea");
    else
        formText = editForm.querySelector("input[type='text']");

    formText.value = formValue;
}

function confirmSelection(editForm, inputField) {
    let formText
    if (inputField.id == "bio")
        formText = editForm.querySelector("textarea").value;
    else
        formText = editForm.querySelector("input[type='text']").value;
    
    sendPostRequest(getRootUrl() + "/api/user", {field: inputField.id, value: formText}, 
    function() {
        if (parseInt(this.responseText)) {
            inputField.children[0].innerHTML = escapeHtml(formText);
            resetSelection(editForm, inputField);
        }
        else if (parseInt(this.responseText) == 0) 
            resetSelection(editForm, inputField);
        else 
            window.location = this.responseText;
    });
}

function resetSelection(editForm, inputField) {
    editForm.style.display = "none";
    inputField.style.display = "flex";
}

function createTileLists() {
    const user = document.querySelector("#username > strong");
    sendPostRequest(getRootUrl() + "/api/user", {userLists: user.innerHTML}, function() {
        const res = JSON.parse(this.responseText);
        const pets = res.pets;
        const lists = res.lists;

        const petGridContent = document.querySelector('#userPets > .petGrid > .petGridContent');

        for (const pet of pets) {
            const tile = createTile(`pet/${pet.id}`, `images/petProfilePictures/${pet.id}.jpg`, pet.name, null, pet.description, null, false);
            petGridContent.appendChild(tile);
        }

        const listElements = document.querySelectorAll("#lists .petGrid .petGridContent");
        for (let i = 0; i < listElements.length; i++) {
            for (const pet of lists[i]) {
                const tile = createTile(`pet/${pet.id}`, `images/petProfilePictures/${pet.id}.jpg`, pet.name, null, pet.description, null, false);
                listElements[i].appendChild(tile);
            }
        }
    });
}

function escapeHtml(string) {
    let entityMap = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': '&quot;',
        "'": '&#39;',
        "/": '&#x2F;'
    };

    return String(string).replace(/[&<>"'\/]/g, function (s) {
        return entityMap[s];
    });
}