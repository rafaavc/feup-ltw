import { getRootUrl, initWebsite } from './modules/init.js'
import { sendPutRequest, sendGetRequest } from './modules/ajax.js'
import { createPetTile } from './modules/tile.js'
import { toggleAddingMode, showUpdatedField, askForDeleteConfirm } from './modules/add_field.js'
import { escapeHtml } from './modules/escape.js'
import { getCSRF } from './modules/utils.js'

const bioEmptyMessage = "Bio (currently empty)";

const editProfileButton = document.getElementById("editProfile");
if (editProfileButton != null)
    editProfileButton.addEventListener('click', editProfile);

const forms = Array.from(document.getElementsByClassName('textButtonPair'));
forms.forEach(form => {
    const inputField = form.children[0];
    const editForm = form.children[1];

    const edit = inputField.getElementsByClassName("clickable");
    edit[0].addEventListener('click', () => {
        showSelection(editForm, inputField);
    });

    const confirm = editForm.getElementsByClassName("confirm");
    confirm[0].addEventListener('click', (event) => {
        event.preventDefault();
        if (inputField.id == "password")
            createNewPassword(editForm, inputField);
        else
            confirmSelection(editForm, inputField);
    });

    const close = editForm.getElementsByClassName("close");
    close[0].addEventListener('click', (event) => {
        event.preventDefault();
        resetSelection(editForm, inputField);
    });

});

const lists = document.getElementById('lists');
const listSelect = document.getElementById('listSelect');
const listsPetGrid = lists == null ? null : lists.querySelector('div.list');
if (listsPetGrid == null) listSelect.style.display = "none";
listSelect.addEventListener('change', updateSelectedList);

function updateSelectedList() {
    for (const list of lists.children) {
        if (list.dataset.id == listSelect.options[listSelect.selectedIndex].value)
            list.style.display = 'grid';
        else list.style.display = 'none';
    }
}

const addListButton = document.getElementById('addListButton');
if (addListButton != null)
    addListButton.addEventListener('click', function(e) {
        toggleAddingMode.bind(this)(e, null, function() {
            updateSelectedList();
            listSelect.style.display = "block";
        });
    });

const removeListButton = document.getElementById("removeListButton");
if (removeListButton != null)
    removeListButton.addEventListener('click', askForDeleteConfirm);

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

        //check if bio field is empty
        const bio = document.querySelector("#bio > p");
        if (bio.innerHTML === "") {
            bio.innerHTML = bioEmptyMessage;
        }
    }
    else {
        editProfileLabel.innerHTML = "Edit profile";
        editFields.forEach(field => field.style.display = "none");
        forms.forEach(form => form.style.display = "none");
        initialFields.forEach(field => field.style.display = "flex");

        //check if bio field is empty
        const bio = document.querySelector("#bio > p");
        if (bio.innerHTML === bioEmptyMessage) {
            bio.innerHTML = "";
        }
    }
}

function showSelection(editForm, inputField) {
    editForm.style.display = "flex";
    editForm.style.margin = "0.5rem 0";
    editForm.style.alignItems = "baseline"
    inputField.style.display = "none";

    let formText;
    const formValue = inputField.children[0].innerHTML;

    if (inputField.id == "bio") {
        formText = editForm.querySelector("textarea");
        if (formValue === bioEmptyMessage) {
            formText.value = "";
            return;
        }
    }
    else
        formText = editForm.getElementsByClassName("edit-data")[0];

    if (inputField.id != "password")
        formText.value = formValue;
}

function confirmSelection(editForm, inputField) {
    let formText
    if (inputField.id == "bio")
        formText = editForm.querySelector("textarea").value;
    else
        formText = editForm.getElementsByClassName("edit-data")[0].value;

    sendPutRequest(getRootUrl() + "/api/user", { field: inputField.id, value: formText, csrf: getCSRF() },
        function () {
            const res = JSON.parse(this.responseText);
            if (res.success) {
                inputField.children[0].innerHTML = escapeHtml(formText);
                if (inputField.id === "bio" && formText === "") 
                        inputField.children[0].innerHTML = bioEmptyMessage;
                
                resetSelection(editForm, inputField);
                showUpdatedField(res.message, inputField, false, "updateField");
            }
            else
                showUpdatedField(res.message, editForm, true, "updateField");
            
            if (inputField.id == "username" && res.success)
                window.location = res.updateUrl;
        });
}

function createNewPassword(editForm, inputField) {
    const currentPassword = document.getElementById("currentPassword").value;
    const newPassword = document.getElementById("newPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    resetPassword();

    if (newPassword.length < 8) {
        showUpdatedField("New password has to have at least 8 characters", editForm, true, "updateField");
        return;
    }

    if (!(newPassword === confirmPassword)) {
        showUpdatedField("Passwords do not match", editForm, true, "updateField");
        return;
    }

    sendPutRequest(getRootUrl() + "/api/user",
        { currentPassword: currentPassword, newPassword: newPassword, confirmPassword: confirmPassword, csrf: getCSRF() },
        function () {
            const res = JSON.parse(this.responseText);
            console.log(res);
            if (res.success) {
                resetSelection(editForm, inputField);
                showUpdatedField(res.message, inputField, false, "updateField");
            }
        });
}

function resetSelection(editForm, inputField) {
    editForm.style.display = "none";
    inputField.style.display = "flex";
    if (inputField.id == "password") resetPassword();
}

function resetPassword() {
    document.getElementById("currentPassword").value = "";
    document.getElementById("newPassword").value = "";
    document.getElementById("confirmPassword").value = "";
}

function createTileLists() {
    const user = document.querySelector("#username > strong");
    sendGetRequest(`${getRootUrl()}/api/user/${user.innerHTML}/${getCSRF()}`, function () {
        const res = JSON.parse(this.responseText);
        const pets = res.pets;
        const lists = res.lists;

        const petGridContent = document.querySelector('#userPets > .petGrid > .petGridContent');
        if (pets.length == 0) {
            const parent = petGridContent.parentNode;
            parent.innerHTML = '';
            const pElem = document.createElement('p');
            pElem.style.marginTop = '0';
            pElem.appendChild(document.createTextNode(`@${user.innerHTML} has no pets.`));
            parent.appendChild(pElem);
            parent.style.gridTemplateColumns = "1fr";
            
        } else {
            for (const pet of pets) {
                const tile = createPetTile(pet);
                petGridContent.appendChild(tile);
            }
        }

        const listElements = document.querySelectorAll("#lists .petGrid .petGridContent");
        for (let i = 0; i < listElements.length; i++) {
            if (lists[i].length == 0) {
                const pElem = document.createElement('p');
                pElem.style.marginTop = '0';
                pElem.appendChild(document.createTextNode('This list has no pets.'));
                listElements[i].appendChild(pElem);
            } else {
                for (const pet of lists[i]) {
                    const tile = createPetTile(pet);
                    listElements[i].appendChild(tile);
                }
            }
        }
    });
}
