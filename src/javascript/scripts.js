
let menuState = false;

function getHeader() {
    return document.querySelector('body > header');
}

function scrollHandler() {
    const offsetFromTop = 30;
    if (window.scrollY > offsetFromTop && !menuState) {
        showMenuBackground();
    } else if (window.scrollY <= offsetFromTop && menuState) {
        hideMenuBackground();
    }
}

function showMenuBackground() {
    const menu = getHeader();
    menuState = true;
    menu.classList.add('filledMenu');
}

function hideMenuBackground() {
    const menu = getHeader();
    menuState = false;
    menu.classList.remove('filledMenu');
}

function movePetGrid(petGrid, right) {
    if (petGrid.children[1] != undefined && petGrid.children[1].className == "petGridContent") {
        const content = petGrid.children[1];
        content.scrollBy(right ? 200 : -200, 0);
    }
}

function sendGetRequest(whereTo, params, onload) {
    const req = new XMLHttpRequest();
    req.open('GET', whereTo + "/" + params.join('/'));
    req.onload = onload;
    req.send();
}

function sendPostRequest(whereTo, data, onload) {
    const req = new XMLHttpRequest();
    req.open('POST', whereTo, true);
    req.onload = onload;
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    req.send(encodeForAjax(data));
    console.log("request sent", encodeForAjax(data));
}

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

function initWebsite() {
    if (document.querySelector('section:first-of-type.indexCover') != null) {
        window.addEventListener('scroll', scrollHandler);
        scrollHandler(); // In case the webpage shows up already scrolled
    } else {
        showMenuBackground();
    }

    // these two lines make sure that the footer is always at the bottom
    document.body.style.minHeight = window.innerHeight + "px";
    document.body.style.paddingBottom = document.querySelector('body > footer').clientHeight + "px";

    const rightArrows = document.querySelectorAll('.petGrid > .arrow.right');
    const leftArrows = document.querySelectorAll('.petGrid > .arrow.left');

    for (const rightArrow of rightArrows) rightArrow.addEventListener('click', () => movePetGrid(rightArrow.parentElement, true));
    for (const leftArrow of leftArrows) leftArrow.addEventListener('click', () => movePetGrid(leftArrow.parentElement, false));

    // edit profile
    // let editProfile = document.getElementById("editProfile");
    // editProfile.addEventListener('click', () => editProfile()); ?editProfile not a function?

    let sections = document.querySelectorAll(".textButtonPair");
    for (let section of sections) {
        let intialContent = section.children[0];
        let editForm = section.children[1];
    }

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
}

initWebsite();