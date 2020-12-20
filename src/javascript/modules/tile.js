import { getRootUrl } from './init.js'
import { getCurrentState } from './pet_state.js';

export const createTile = (url, imageUrl, title, footer, description, extraSection) => {
    const aNode = document.createElement("a");
    aNode.classList.add('tileContainer');
    aNode.href = getRootUrl() + '/' + url;

    const articleNode = document.createElement("article");
    articleNode.classList.add('tile');
    aNode.appendChild(articleNode);

    const imgNode = document.createElement("img");
    imgNode.classList.add('image');
    imgNode.style.background = `url('${getRootUrl()}/${imageUrl}')`;
    imgNode.style.backgroundPosition = "50%";
    imgNode.style.backgroundSize = "cover";
    articleNode.appendChild(imgNode);

    const headerNode = document.createElement("header");
    const h3Node = document.createElement("h3");
    h3Node.appendChild(document.createTextNode(title));
    headerNode.appendChild(h3Node);
    articleNode.appendChild(headerNode);

    if (extraSection != null) {
        articleNode.appendChild(extraSection);
    }

    if (description != null) {
        const pNode = document.createElement('p');
        pNode.appendChild(document.createTextNode(description));
        pNode.style.overflow = 'hidden';
        if (title == '') pNode.style.margin = '3.5rem 1rem 1rem 1rem';
        articleNode.appendChild(pNode);
    }

    if (footer != null) {
        const footerNode = document.createElement('footer');
        footerNode.appendChild(footer);
        articleNode.appendChild(footerNode);
    }

    return aNode;
}

export const createPetTile = (pet) => {
    const spanElem = document.createElement('span');
    spanElem.classList.add('tagLabel');
    spanElem.classList.add(pet.state);
    spanElem.appendChild(document.createTextNode(getCurrentState(pet.state)));

    const name = pet.name == '' || pet.name == null ? `${pet.size} ${pet.color} ${pet.specie}` : pet.name;
    return createTile(`pet/${pet.id}`, `images/pet_profile_pictures/${pet.id}.jpg`, name, null, pet.description, spanElem);
}


export const createUserTile = (user) => {
    const iconElem = document.createElement('i');
    iconElem.classList.add('icofont-dog');
    const footer = document.createElement('span');
    footer.appendChild(document.createTextNode(user.petCount == null ? 0 : user.petCount));
    footer.appendChild(iconElem);
    return createTile(`user/${user.username}`, `images/user_profile_pictures/${user.id}.jpg`, user.name, footer, user.description, null);
}

