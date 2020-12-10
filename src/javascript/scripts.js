
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
}


initWebsite();
