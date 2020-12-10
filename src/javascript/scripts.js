
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

    let closeButtons = document.querySelectorAll(".textButtonPair form .close");
    closeButtons.forEach(closeButton => closeButton.addEventListener('click', (event) => {
        event.preventDefault();
    }))
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


initWebsite();
