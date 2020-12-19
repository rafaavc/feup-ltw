import { getRootUrl, initWebsite } from '../init.js'
import { sendPostRequest } from '../ajax.js'
import { createPetTile } from '../tile.js'
import { toggleAddingMode } from '../add_field.js'
import { escapeHtml } from '../escape.js'

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
const listSelect = document.getElementById('list-select');
listSelect.addEventListener('change', () => {
    for (const list of lists.children) {
        if (list.dataset.id == listSelect.options[listSelect.selectedIndex].value)
            list.style.display = 'grid';
        else list.style.display = 'none';
    }
});

const addListButton = document.getElementById('addListButton');
if (addListButton != null)
    addListButton.addEventListener('click', toggleAddingMode);

const removeListButton = document.getElementById("removeListButton");
if (removeListButton != null)
    removeListButton.addEventListener('click', removeList);

createTileLists();
initWebsite();

function removeList() {
    const listSelect = document.getElementById("list-select");
    const selectedIndex = listSelect.selectedIndex;

    //delete element from select
    const elementToDelete = listSelect.children[selectedIndex];
    if (elementToDelete == undefined) return;
    const listId = elementToDelete.value;
    elementToDelete.remove();

    //delete list
    document.querySelector("#lists > div[data-id='" + listId + "']").remove();
    const firstList = document.getElementById("lists");
    if (firstList.children[0].length != 0)
        firstList.children[0].style.display = "grid";

    //delete list in database
    sendPostRequest(getRootUrl() + "/api/user", { listId: listId }, function () { });
}

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
    editForm.style.margin = "1rem";
    editForm.style.alignItems = "baseline"
    inputField.style.display = "none";

    let formText;
    const formValue = inputField.children[0].innerHTML;

    if (inputField.id == "bio")
        formText = editForm.querySelector("textarea");
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

    sendPostRequest(getRootUrl() + "/api/user", { field: inputField.id, value: formText },
        function () {
            const res = JSON.parse(this.responseText);
            if (res.success) {
                inputField.children[0].innerHTML = escapeHtml(formText);
                resetSelection(editForm, inputField);
                showUpdatedField(res.message, inputField, false);
            }
            else
                showUpdatedField(res.message, editForm, true);
            
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
        showUpdatedField("New password has to have at least 8 characters", editForm, true);
        return;
    }

    if (!(newPassword === confirmPassword)) {
        showUpdatedField("Passwords do not match", editForm, true);
        return;
    }

    sendPostRequest(getRootUrl() + "/api/user",
        { currentPassword: currentPassword, newPassword: newPassword, confirmPassword: confirmPassword },
        function () {
            const res = JSON.parse(this.responseText);
            console.log(res);
            if (res.success) {
                resetSelection(editForm, inputField);
                showUpdatedField(res.message, inputField, false);
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
    sendPostRequest(getRootUrl() + "/api/user", { userLists: user.innerHTML }, function () {
        const res = JSON.parse(this.responseText);
        const pets = res.pets;
        const lists = res.lists;

        const petGridContent = document.querySelector('#userPets > .petGrid > .petGridContent');
        for (const pet of pets) {
            const tile = createPetTile(pet);
            petGridContent.appendChild(tile);
        }

        const listElements = document.querySelectorAll("#lists .petGrid .petGridContent");
        for (let i = 0; i < listElements.length; i++) {
            for (const pet of lists[i]) {
                const tile = createPetTile(pet);
                listElements[i].appendChild(tile);
            }
        }
    });
}

function showUpdatedField(message, block, errorMessage) {
    let p = block.getElementsByClassName('updateMessage')[0];
    if (p == undefined) {
        p = document.createElement('p');
        p.className = "updateMessage";
    }

    p.innerHTML = message;
    p.style.fontSize = '0.8rem';
    p.style.color = errorMessage ? 'red' : 'green';
    block.appendChild(p);
    setTimeout(function () { p.remove() }, 3000);
}
