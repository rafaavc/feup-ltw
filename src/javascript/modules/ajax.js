const encodeForAjax = (data) => {
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

export const sendGetRequest = (whereTo, onload) => {
    const req = new XMLHttpRequest();
    req.open('GET', whereTo, true);
    req.addEventListener('load', function() {
        console.log(this.responseText);
        if (this.status != 200) return;
        onload.bind(this)();
    });
    req.send();
}

export const sendSyncGetRequest = (whereTo, onload) => {
    const req = new XMLHttpRequest();
    req.open('GET', whereTo, false);
    req.send();

    console.log(req.responseText);
    if (req.status != 200) return;
    onload(req);
}

export const sendDeleteRequest = (whereTo, onload) => {
    const request = new XMLHttpRequest();
	request.addEventListener('load', function() {
        console.log(this.responseText);
        if (this.status != 200) return;
        onload.bind(this)();
    });
	request.open("DELETE", whereTo, true);
	request.send();
}

export const sendPostRequest = (whereTo, params, onload) => {
    const request = new XMLHttpRequest();
	request.addEventListener('load', function() {
        console.log(this.responseText);
        if (this.status != 200) return;
        onload.bind(this)();
    });
	request.open("POST", whereTo, true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send(encodeForAjax(params));
}

export const sendPutRequest = (whereTo, params, onload) => {
    const request = new XMLHttpRequest();
    console.log('sending', params)
	request.addEventListener('load', function() {
        console.log(this.responseText);
        if (this.status != 200) return;
        onload.bind(this)();
    });
	request.open("PUT", whereTo, true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send(encodeForAjax(params));
}

