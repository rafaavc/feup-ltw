import { sendPostRequest } from "./ajax.js";
import { getRootUrl } from './init.js';

export function toggleAddingMode(e) {
    if (e != undefined) e.preventDefault();
    const entity = this.dataset.entity;
    if (this.dataset.savedValue != undefined && this.dataset.savedValue != "") { // creating
        this.innerHTML = this.dataset.savedValue;
        this.dataset.savedValue = '';

        const input = document.getElementById(`${entity}Input`);
        input.nextSibling.remove();
        input.remove();
    } else {   // opening
        this.dataset.savedValue = this.innerHTML;
        this.innerHTML = '';
        const icon = document.createElement('i');
        icon.classList.add("icofont-ui-close");

        this.appendChild(icon);
        this.appendChild(document.createTextNode('Cancel'));

        const input = document.createElement("input");
        input.type = "text";

        const publicSelect = document.createElement("select");
        const description = document.createElement("textarea");

        if (entity == 'List') {
            const addListForm = document.createElement("form");
            addListForm.id = `${entity}Input`;

            input.id = "newListTitle";
            input.placeholder = "Title";
            addListForm.appendChild(input);

            const privateOption = document.createElement("option");
            privateOption.value = "Private";
            privateOption.innerHTML = "Private";
            publicSelect.appendChild(privateOption);

            const publicOption = document.createElement("option");
            publicOption.value = "Public";
            publicOption.innerHTML = "Public";
            publicSelect.appendChild(publicOption);

            addListForm.appendChild(publicSelect);

            description.id = "addDescription";
            description.placeholder = "Description";
            addListForm.appendChild(description);

            this.parentNode.parentNode.appendChild(addListForm);
        }
        else {
            input.id = `${entity}Input`;
            input.placeholder = entity;
            this.parentNode.parentNode.appendChild(input);
        }

        const context = this;
        const button = document.createElement('button');
        button.classList.add('simpleButton');
        button.classList.add('contrastButton');
        button.appendChild(document.createTextNode(`Add ${entity}`));
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const option = document.createElement('option');
            option.value = input.value;
            option.appendChild(document.createTextNode(input.value));
            option.selected = true;
            
            document.querySelector(`select[name=${entity.toLowerCase()}]`).appendChild(option);
            toggleAddingMode.bind(context)();

            if (entity == 'List') {
                sendPostRequest(getRootUrl() + "/api/user", 
                                {title: input.value, visibility: publicSelect.selectedIndex, description: description.innerHTML}, 
                                function() {
                    const res = JSON.parse(this.responseText);
            
                    console.log(res);
                })
            }

        });

        this.parentNode.parentNode.appendChild(button);
    }
}