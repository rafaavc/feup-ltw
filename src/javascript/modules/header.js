
let headerState = false;
let menuState = false;
const header = document.querySelector('body > header');

const scrollHandler = () => {
    const offsetFromTop = 30;
    if (window.scrollY > offsetFromTop && !headerState) {
        showHeaderBackground();
    } else if (window.scrollY <= offsetFromTop && headerState) {
        hideHeaderBackground();
    }
}

export const showHeaderBackground = () => {
    headerState = true;
    header.classList.add('filledMenu');
}

export const hideHeaderBackground = () => {
    if (menuState) return;
    headerState = false;
    header.classList.remove('filledMenu');
}

export const initHeader = () => {
    if (document.querySelector('section:first-of-type.indexCover') != null) {
        const hamburguer = document.getElementById('hamburguer');
        hamburguer.addEventListener('change', function() {
            if (this.checked) {
                showHeaderBackground();
                menuState = true;
            } else { 
                hideHeaderBackground();
                menuState = false;
            }
            scrollHandler();
        });
        window.addEventListener('scroll', scrollHandler);
        scrollHandler(); // In case the webpage shows up already scrolled
    } else {
        showHeaderBackground();
    }
}


