
import { initHeader } from './header.js'

function movePetGrid(petGrid, right) {
    if (petGrid.children[1] != undefined && petGrid.children[1].className == "petGridContent") {
        const content = petGrid.children[1];
        content.scrollBy(right ? 200 : -200, 0);
    }
}

function initWebsite() {

    initHeader();

    // these two lines make sure that the footer is always at the bottom
    document.body.style.minHeight = window.innerHeight + "px";
    document.body.style.paddingBottom = document.querySelector('body > footer').clientHeight + "px";

    const firstSection = document.querySelector('body > section:first-of-type');
    const fsPaddingTop = window.getComputedStyle(firstSection).getPropertyValue('padding-top');

    firstSection.style.paddingTop = (document.querySelector('body > header').offsetHeight + parseInt(fsPaddingTop)) + "px";


    const rightArrows = document.querySelectorAll('.petGrid > .arrow.right');
    const leftArrows = document.querySelectorAll('.petGrid > .arrow.left');

    for (const rightArrow of rightArrows) rightArrow.addEventListener('click', () => movePetGrid(rightArrow.parentElement, true));
    for (const leftArrow of leftArrows) leftArrow.addEventListener('click', () => movePetGrid(leftArrow.parentElement, false));
}


initWebsite();

export const getRootUrl = () => {
    return window.location.protocol + '//' + window.location.host;
}
