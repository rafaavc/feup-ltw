
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

function initWebsite() {
    if (document.querySelector('section:first-of-type.indexCover') != null) {
        window.addEventListener('scroll', scrollHandler);
        scrollHandler(); // In case the webpage shows up already scrolled
    } else {
        showMenuBackground();
    }
    const rightArrows = document.querySelectorAll('.petGrid > .arrow.right');
    const leftArrows = document.querySelectorAll('.petGrid > .arrow.left');

    for (const rightArrow of rightArrows) rightArrow.addEventListener('click', () => movePetGrid(rightArrow.parentElement, true));
    for (const leftArrow of leftArrows) leftArrow.addEventListener('click', () => movePetGrid(leftArrow.parentElement, false));
}

function editProfile() {
    let editProfileLabel = document.querySelector("#editProfileLabel > a");
    let editProfile = document.getElementById("editProfile");
    let inconLabels = document.querySelectorAll(".textButtonPair > label");
    let forms = document.querySelectorAll(".textButtonPair form");
    let editFields = document.querySelectorAll(".textButtonPair .edit");

    if (editProfile.checked) {
        editProfileLabel.innerHTML = "Save changes";
        editFields.forEach(field => field.style.display = "inline-block");
        inconLabels.forEach(label => label.innerHTML = "Edit");
    }
    else {
        editProfileLabel.innerHTML = "Edit profile";
        editFields.forEach(field => field.style.display = "none");
        forms.forEach(form => form.style.display = "none");
    }

    console.log(editProfile);
}

function showField(inputForm, inputId) {
    let edit = document.getElementById(inputId);
    edit.style.display = "none";

    let editForm = document.getElementById(inputForm);
    editForm.style.display = "block";
}

function resetSelection(inputForm, inputId) {
    let form = document.getElementById(inputForm);
    form.style.display = "none";

    let edit = document.getElementById(inputId);
    edit.style.display = inputId == "bio" ? "flex" : "inline-block";
}


initWebsite();
