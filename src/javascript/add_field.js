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

        if (entity == 'List') {
            const addListForm = document.createElement("form");
            addListForm.id = `${entity}Input`;

            const title = document.createElement("input");
            title.type = "text";
            title.id = "newListTitle";
            title.placeholder = "Title";
            addListForm.appendChild(title);

            const publicSelect = document.createElement("select");

            const publicOption = document.createElement("option");
            publicOption.value = "Public";
            publicOption.innerHTML = "public";
            publicSelect.appendChild(publicOption);

            const privateOption = document.createElement("option");
            privateOption.value = "Private";
            privateOption.innerHTML = "private";
            publicSelect.appendChild(privateOption);

            addListForm.appendChild(publicSelect);

            const description = document.createElement("input");
            description.type = "text";
            description.id = "addDescription";
            description.placeholder = "Description";
            addListForm.appendChild(description);

            this.parentNode.parentNode.appendChild(addListForm);
        }
        else {
            const input = document.createElement("input");
            input.type = "text";
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
        });

        this.parentNode.parentNode.appendChild(button);
    }
}