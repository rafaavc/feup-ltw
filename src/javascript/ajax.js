
export const sendGetRequest = (whereTo, params, onload) => {
    const req = new XMLHttpRequest();
    req.open('GET', whereTo + "/" + params.join('/'));
    req.onload = onload;
    req.send();
}

