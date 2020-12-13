const encodeForAjax = (data) => {
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

export const sendGetRequest = (whereTo, params, onload) => {
    const req = new XMLHttpRequest();
    req.open('GET', whereTo + "/" + params.join('/'), true);
    req.onload = onload;
    req.send();
}

export const sendPostRequest = (whereTo, params, onload) => {
    const request = new XMLHttpRequest();
	request.addEventListener('load', onload);
	request.open("POST", whereTo, true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send(encodeForAjax(params));
}

