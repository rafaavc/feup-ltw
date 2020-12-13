import { getRootUrl, initWebsite } from '../init.js'
import { sendPostRequest } from '../ajax.js'

let editProfileButton = document.getElementById("editProfile");
if (editProfileButton != null)
    editProfileButton.addEventListener('click', editProfile);

let forms = Array.from(document.getElementsByClassName('textButtonPair'));
forms.forEach(form => {
    let inputField = form.children[0];
    let editForm = form.children[1];
    let input = editForm.querySelector("input[type='text']");
    
    let edit = inputField.getElementsByClassName("edit");
    edit[0].addEventListener('click', (event) => {
        showSelection(editForm.id, inputField.id);
    });

    let confirm = editForm.getElementsByClassName("confirm");
    confirm[0].addEventListener('click', (event) => {
        event.preventDefault();
        confirmSelection(editForm.id, inputField.id);
    });

    let close = editForm.getElementsByClassName("close");
    close[0].addEventListener('click', (event) => {
        event.preventDefault();
        resetSelection(editForm.id, inputField.id);
    });
    
});

let lists = document.getElementById('lists');
for (let i = 1; i < lists.children.length; i++){
    lists.children[i].style.display = "none";
}

let listSelect = document.getElementById('list-select');
listSelect.addEventListener('change', () => {
    for (let option of listSelect.options) {
        if (option.value - 1 == listSelect.options.selectedIndex)
            lists.children[option.value - 1].style.display = 'grid';
        else lists.children[option.value - 1].style.display = 'none';
    }
});

initWebsite();

function editProfile() {
    let editProfileLabel = document.querySelector("#editProfileLabel > a");
    let editProfile = document.getElementById("editProfile");
    let forms = document.querySelectorAll(".textButtonPair form");
    let editFields = document.querySelectorAll(".textButtonPair .edit");
    let initialFields = document.querySelectorAll(".textButtonPair > div");

    if (editProfile.checked) {
        editProfileLabel.innerHTML = "Close edition";
        editFields.forEach(field => field.style.display = "inline-block");
    }
    else {
        editProfileLabel.innerHTML = "Edit profile";
        editFields.forEach(field => field.style.display = "none");
        forms.forEach(form => form.style.display = "none");
        initialFields.forEach(field => field.id == "bio" ? field.style.display = "flex" : field.style.display = "block");
    }
}

function showSelection(inputForm, inputId) {
    let edit = document.getElementById(inputId);
    edit.style.display = "none";

    let editForm = document.getElementById(inputForm);
    editForm.style.display = "flex";

    let formText = document.querySelector("#" + inputForm + " input[type='text']");
    let formValue = document.querySelector("#" + inputId + " :first-child").innerHTML;
    if (inputId == "username") formValue = formValue.substr(1);
    formText.value = formValue;
}

function resetSelection(inputForm, inputId) {
    let form = document.getElementById(inputForm);
    form.reset();
    form.style.display = "none";

    let edit = document.getElementById(inputId);
    edit.style.display = inputId == "bio" ? "flex" : "inline-block";
}

function confirmSelection(inputForm, inputId) {
    let formText = document.querySelector("#" + inputForm + " input[type='text']").value;
    sendPostRequest(getRootUrl() + "/api/user", {field: inputId, value: formText}, 
    function() {
        if (parseInt(this.responseText)) {
            let newValue = escapeHtml(formText);
            if (inputId == "username") newValue = "@" + newValue;
            document.querySelector("#" + inputId).children[0].innerHTML = newValue;

            resetSelection(inputForm, inputId);
        }
        else {
            console.log(this.responseText);
            window.location = this.responseText;
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