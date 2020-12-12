import { initWebsite } from '../init.js'

console.log("profile page");

let editProfileButton = document.getElementById("editProfile");
editProfileButton.addEventListener('click', editProfile);

let closeButtons = document.querySelectorAll(".textButtonPair form .close");
closeButtons.forEach(closeButton => closeButton.addEventListener('click', (event) => {
    event.preventDefault();
}));

let confirmButtons = document.querySelectorAll(".textButtonPair form .confirm");
confirmButtons.forEach(confirmButton => confirmButton.addEventListener('click', (event) => {
    event.preventDefault();
}));

let lists = document.getElementById('lists');
for (let i = 1; i < lists.children.length; i++){
    lists.children[i].style.display = "none";
}

let listSelect = document.getElementById('list-select');
listSelect.addEventListener('change', () => {
    for (let option of listSelect.options) {
        console.log(option.value - 1, listSelect.options.selectedIndex);
        if (option.value - 1 == listSelect.options.selectedIndex) {
            lists.children[option.value - 1].style.display = 'grid';
        }
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

function showField(inputForm, inputId) {
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

function confirmSelection(rootUrl, inputForm, inputId) {
    let formText = document.querySelector("#" + inputForm + " input[type='text']").value;
    sendPostRequest(rootUrl + "/api/user", {field: inputId, value: formText}, 
    function() {
        if (parseInt(this.responseText)) {
            let newValue = escapeHtml(formText);
            console.log(inputId);
            if (inputId == "username") newValue = "@" + newValue;
            document.querySelector("#" + inputId).children[0].innerHTML = newValue;

            resetSelection(inputForm, inputId);
        }
    });
}

function encodeForAjax(data) {
    return Object.keys(data).map(function(k){
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}