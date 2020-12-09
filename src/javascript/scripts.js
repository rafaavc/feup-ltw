
let menuState = false;

function getHeader() {
    return document.querySelector('body > header');
}

function scrollHandler() {
    const offsetFromTop = 30;
    if (window.scrollY > 30 && !menuState) {
        const menu = getHeader();
        menuState = true;
        menu.classList.add('filledMenu');
    } else if (window.scrollY <= 30 && menuState) {
        const menu = getHeader();
        menuState = false;
        menu.classList.remove('filledMenu');
    }
}

function movePetGrid(petGrid, right) {
    if (petGrid.children[1] != undefined && petGrid.children[1].className == "petGridContent") {
        const content = petGrid.children[1];
        content.scrollBy(right ? 200 : -200, 0);
    }
}

function initWebsite() {
    window.addEventListener('scroll', scrollHandler);
    const rightArrows = document.querySelectorAll('.petGrid > .arrow.right');
    const leftArrows = document.querySelectorAll('.petGrid > .arrow.left');

    for (const rightArrow of rightArrows) rightArrow.addEventListener('click', () => movePetGrid(rightArrow.parentElement, true));
    for (const leftArrow of leftArrows) leftArrow.addEventListener('click', () => movePetGrid(leftArrow.parentElement, false));
}

function editOptions() {
    let editProfileLabel = document.querySelector("#editProfileLabel > a");
    let editProfile = document.getElementById("editProfile");

    let editFields = document.querySelectorAll(".textButtonPair .edit");
    console.log(editFields);
    

    if (editProfile.checked) {
         editProfileLabel.innerHTML = "Save changes";
         editFields.forEach(e => e.style.display = "inline-block");
    }
    else {
        editProfileLabel.innerHTML = "Edit profile";
        editFields.forEach(e => e.style.display = "none");
    } 

    console.log(editProfile);
}


initWebsite();
