import { getRootUrl } from './init.js'

export const createTile = (url, imageUrl, title, footer, description, extraSection, slider) => {
    const aNode = document.createElement("a");
    aNode.classList.add('tileContainer');
    aNode.href = document.body.dataset.rootUrl + '/' + url;

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
        articleNode.appendChild(pNode);
    }

    if (footer != null) {
        const footerNode = document.createElement('footer');
        footerNode.appendChild(footer);
        articleNode.appendChild(footerNode);
    }

    return aNode;
}

