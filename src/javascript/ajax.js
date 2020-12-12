
export const sendGetRequest = (whereTo, params, onload) => {
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