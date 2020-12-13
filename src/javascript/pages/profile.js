import { getRootUrl, initWebsite } from '../init.js'
import { sendPostRequest } from '../ajax.js'
import { createTile } from '../tile.js'

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

const listSelect = document.getElementById('list-select');
listSelect.addEventListener('change', () => {
    for (let option of listSelect.options) {
        if (option.value - 1 == listSelect.options.selectedIndex)
            lists.children[option.value - 1].style.display = 'grid';
        else lists.children[option.value - 1].style.display = 'none';
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
        console.log(this.responseText);
        const res = JSON.parse(this.responseText);
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