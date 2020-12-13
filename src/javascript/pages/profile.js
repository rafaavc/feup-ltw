import { getRootUrl, initWebsite } from '../init.js'
import { sendPostRequest } from '../ajax.js'

let editProfileButton = document.getElementById("editProfile");
if (editProfileButton != null)
    editProfileButton.addEventListener('click', editProfile);

let forms = Array.from(document.getElementsByClassName('textButtonPair'));
forms.forEach(form => {
    let inputField = form.children[0];
    let editForm = form.children[1];
    
    let edit = inputField.getElementsByClassName("edit");
    edit[0].addEventListener('click', () => {
        showSelection(editForm, inputField);
    });

    let confirm = editForm.getElementsByClassName("confirm");
    confirm[0].addEventListener('click', (event) => {
        event.preventDefault();
        confirmSelection(editForm, inputField);
    });

    let close = editForm.getElementsByClassName("close");
    close[0].addEventListener('click', (event) => {
        event.preventDefault();
        resetSelection(editForm, inputField);
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
    if (inputField.id == "username") formValue = formValue.substr(1);

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
            let newValue = escapeHtml(formText);
            if (inputField.id == "username") newValue = "@" + newValue;
            inputField.children[0].innerHTML = newValue;

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