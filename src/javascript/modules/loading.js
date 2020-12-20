
export const makeLoading = (element) => {
    element.innerHTML = "";
    const loader = document.createElement('div');
    loader.classList.add('loading');
    element.appendChild(loader);
}
