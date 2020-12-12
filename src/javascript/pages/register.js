import { sendGetRequest } from '../ajax.js'
import { initWebsite } from '../init.js'

initWebsite();

function onElementChange() {
    const elementInput = this;
    if (elementInput.value == "") {
        elementInput.previousSibling.remove();
        return;
    }
    sendGetRequest("api/existence", [elementInput.name, elementInput.value], function() {
        const res = JSON.parse(this.responseText);
        const message = res.value ? `The ${elementInput.name} is already in use.` : `The ${elementInput.name} is not in use.`;
        const color = res.value ? "darkred" : "darkgreen";
        if (elementInput.previousSibling.tagName != 'P') {
            let el = document.createElement('p');
            el.innerHTML = message;
            el.style.fontSize = "0.8em";
            el.style.color = color;
            elementInput.parentNode.insertBefore(el, elementInput);
        } else {
            elementInput.previousSibling.innerHTML = message;
            elementInput.previousSibling.style.color = color;
        }
    });
}

const mail = document.getElementById('mail');
const username = document.getElementById('username');

username.addEventListener('input', onElementChange);
mail.addEventListener('input', onElementChange);

