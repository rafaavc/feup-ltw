import SimpleSlider from './modules/slider.js'
import { sendPostRequest, sendDeleteRequest } from './modules/ajax.js'
import './generic.js'
import { getRootUrl } from './modules/init.js'
import { elapsedTime, getCSRF } from './modules/utils.js'
import { escapeHtml } from './modules/escape.js'

const commentForm = document.querySelector('.petProfileSection > form');
const adoptButton = document.querySelector('#adopt');
const cancelButton = document.querySelector('#cancel');
const editPetButton = document.getElementById('editPet');
const submitEditPetButton = document.getElementById('submitEditPet');
const cancelEditPetButton = document.getElementById('closeEdit');
const petProfile = document.querySelector('.petProfile');
const editPetForm = document.querySelector('form[name=updatePet]');

if (commentForm != null) {
	commentForm.addEventListener('submit', submitComment);
}
if (adoptButton != null) {
	adoptButton.addEventListener('click', proposeToAdoptPet);
}
if (cancelButton != null) {
	cancelButton.addEventListener('click', cancelProposeToAdoptPet);
}
if (editPetButton != null) {
	editPetButton.addEventListener('click', editPet);
}
if (cancelEditPetButton != null) {
	cancelEditPetButton.addEventListener('click', cancelEditPet)
}


function editPet() {
	editPetForm.style.display = 'grid';

	const info = document.getElementById('petInfo');
	info.style.display = 'none';

	const photos = document.getElementById('photosInput');
	photos.style.display = 'flex';

	editPetButton.style.display = 'none';
	submitEditPetButton.style.display = 'inline';
	cancelEditPetButton.style.display = 'inline';
}

function cancelEditPet() {
	const photos = document.getElementById('photosInput');
	photos.style.display = 'none';

	editPetForm.style.display = 'none';

	const info = document.getElementById('petInfo');
	info.style.display = 'initial';

	editPetButton.style.display = 'inline';
	submitEditPetButton.style.display = 'none';
	cancelEditPetButton.style.display = 'none';
}

function submitComment(event) {
	event.preventDefault();

	const petId = petProfile.dataset.id;
	const comment = commentForm['text'].value;

	if (comment.length < 1) return;

	sendPostRequest(getRootUrl() + "/api/pet/comment", { petId: petId, comment: comment, csrf: getCSRF() }, receiveComment);
}

function receiveComment() {
	const res = JSON.parse(this.responseText);
	if (!res.value) return;
	const post = res.post;
	const article = document.createElement('article');
	article.className = 'comment';
	article.id = `post-${post.id}`;

	const image = document.createElement('div');
	image.className = 'image';
	image.setAttribute('style', "background-image: url('../../images/user_profile_pictures/" + post.userId + ".jpg'");

	const content = document.createElement('div');

	const descElem = document.createElement('p');
	descElem.appendChild(document.createTextNode(post.description));
	descElem.classList.add('description');

	const spanElem = document.createElement('span');
	spanElem.classList.add('tagLabel');
	spanElem.classList.add('accent');
	spanElem.appendChild(document.createTextNode('Original Poster'));

	const footer = document.createElement('footer');
	const footerContent = document.createTextNode(`${elapsedTime(post.postDate)} ago, by `);
	const userLink = document.createElement('a');
	userLink.href = `${getRootUrl()}/user/${post.userUsername}`;
	userLink.appendChild(document.createTextNode(post.shortUserName));
	footer.append(footerContent, userLink);

	if (petProfile.dataset.ownerId == post.userId) content.append(spanElem);
	content.append(descElem, footer);
	article.append(image, content);

	commentForm.parentNode.insertBefore(article, commentForm);
	const noCommentsP = commentForm.parentNode.querySelector('h3').nextSibling.nextSibling;
	console.log(noCommentsP)
	if (noCommentsP.tagName == "P")
		noCommentsP.style.display = 'none';
}

const slider = new SimpleSlider("mySlider", 3000);
slider.start();


function proposeToAdoptPet(event) {
	event.preventDefault(event);

	const petId = document.querySelector('.petProfile').dataset.id;

	sendPostRequest(`${getRootUrl()}/api/adoption/${petId}`, { csrf: getCSRF() }, changeAdoptButton);
}

function changeAdoptButton() {
	const res = JSON.parse(this.responseText);
	if (!res.value) return;

	document.querySelector('#adopt').remove();
	const button = document.createElement('button');


	button.id = "cancel";
	button.className = "simpleButton contrastButton";
	button.innerHTML = "Cancel";
	button.addEventListener('click', cancelProposeToAdoptPet);

	const paragraph = document.createElement('p');
	paragraph.innerHTML = "You've proposed to adopt! ";
	paragraph.appendChild(button);

	document.querySelector('.petProfile footer').appendChild(paragraph);

	let buttonToUse = adoptButton;
	if (buttonToUse == null) buttonToUse = cancelButton;

	const userId = buttonToUse.dataset.userId;
	const username = buttonToUse.dataset.username;
	const name = buttonToUse.dataset.userName;

	const petProposal = document.createElement('div');
	petProposal.className = 'petProposal open';
	petProposal.setAttribute('data-id', userId);

	const image = document.createElement('div');
	image.className = 'image';
	image.style = "background-image: url('../../images/user_profile_pictures/" + userId + ".jpg')";

	const a = document.createElement('a');
	a.href = getRootUrl() + '/user/' + username;
	a.innerHTML = escapeHtml(name);

	const p = document.createElement('p');
	p.innerHTML = a.outerHTML + ' wants to adopt this pet';

	petProposal.appendChild(image);
	petProposal.appendChild(p);

	document.getElementById('petProposals').appendChild(petProposal);
	if (document.querySelector('#petProposals > p') != null)
		document.querySelector('#petProposals > p').remove();
}

function cancelProposeToAdoptPet(event) {
	event.preventDefault();

	const petId = document.querySelector('.petProfile').dataset.id;

	sendDeleteRequest(`${getRootUrl()}/api/adoption/${petId}/${getCSRF()}`, changeCancelButton);
}

function changeCancelButton() {
	const res = JSON.parse(this.responseText);
	if (!res.value) return;

	document.querySelector('.petProfile footer > p:last-of-type').remove();
	const button = document.createElement('button');

	button.id = "adopt";
	button.className = "simpleButton contrastButton";
	button.innerHTML = "Adopt";
	button.addEventListener('click', proposeToAdoptPet);

	document.querySelector('.petProfile footer').appendChild(button);

	const userId = document.getElementById('petProposals').dataset.userId;
	let petProposals = Array.from(document.getElementsByClassName('petProposal open'));
	petProposals.forEach((petProposal) => {
		if (petProposal.dataset.id === userId) {
			petProposal.remove();
		}
	});
	petProposals = Array.from(document.getElementsByClassName('petProposal'));
	console.log(petProposals);
	if (petProposals.length === 0) {
		const p = document.createElement('p');
		p.innerHTML = "This pet hasn't had any proposals to adopt yet.";
		document.getElementById('petProposals').appendChild(p);
	}
}





const profilePhotoInput = document.querySelector('input[type=hidden][name=profilePhoto]');
const fileInputButtons = [{ obj: document.querySelector('input[type=file]:last-of-type'), id: 0 }];
fileInputButtons[0].obj.addEventListener('change', handleFileInput);
fileInputButtons[0].obj.style.display = "none";
const photoContainer = document.querySelector('div > div.photos');
const addPhotoButton = document.getElementById('addPhotoButton');
addPhotoButton.addEventListener('click', function (e) {
	e.preventDefault();
	fileInputButtons[fileInputButtons.length - 1].obj.click();
});

const removeButtons = Array.from(document.getElementsByClassName('remove'));
removeButtons.forEach(removeButton => {
	removeButton.addEventListener('click', function () {
		const photoId = this.dataset.id;
		const photos = editPetForm.querySelector('.photos');

		const photo = document.createElement('input');
		photo.name = 'removePhotos[]';
		photo.type = 'hidden';
		photo.value = photoId;
		photos.appendChild(photo);
		
		if (this.previousSibling.previousSibling.classList.contains("profilePicture")) {
			profilePhotoInput.value = '';
		}

		document.getElementById('photo' + photoId).remove();

	});
});

function handleFileInput() {
	const lastButton = fileInputButtons[fileInputButtons.length - 1].obj;
	const lastButtonId = fileInputButtons[fileInputButtons.length - 1].id;
	const nextButton = document.createElement('input');
	nextButton.type = "file";
	nextButton.name = lastButton.name;
	nextButton.addEventListener('change', handleFileInput);
	nextButton.style.display = "none";
	nextButton.accept ="image/jpeg";

	lastButton.style.display = "none";

	lastButton.parentNode.insertBefore(nextButton, lastButton);

	fileInputButtons.push({ obj: nextButton, id: lastButtonId + 1 });

	const file = this.files[0];
	const reader = new FileReader();
	reader.onload = function (e) {
		const imageWrapper = document.createElement('div');

		const image = document.createElement("img");
		image.src = e.target.result;
		image.addEventListener('click', updateProfilePic);
		image.dataset.buttonId = lastButtonId;
		imageWrapper.appendChild(image);

		const removeButton = document.createElement('div');
		removeButton.classList.add('remove');
		removeButton.addEventListener('click', function () {
			const buttonIdx = fileInputButtons.findIndex((el) => el.id === lastButtonId);
			if (this.previousSibling.classList.contains("profilePicture")) {
				profilePhotoInput.value = '';
			}
			fileInputButtons[buttonIdx].obj.remove();
			this.parentNode.remove();

			fileInputButtons.splice(buttonIdx, 1);
		});
		const icon = document.createElement('i');
		icon.classList.add('icofont-ui-close');
		removeButton.appendChild(icon);
		imageWrapper.appendChild(removeButton);

		photoContainer.appendChild(imageWrapper);
	}
	reader.readAsDataURL(file);
}


const previousPhotos = document.querySelectorAll('#photosInput > .photos > div > img');
previousPhotos.forEach((photo) => {
	photo.addEventListener('click', updateProfilePic);
})

function updateProfilePic() {
    const prevProfileImage = document.querySelectorAll('img.profilePicture');
	prevProfileImage.forEach((pi) => pi.classList.remove('profilePicture'));
	
	if (this.dataset.buttonId != undefined) {
		const buttonId = parseInt(this.dataset.buttonId);
		const button = fileInputButtons.find((button) => button.id === buttonId).obj;
		profilePhotoInput.value = button.files[0].name;
	} else {
		const photoId = parseInt(this.dataset.photoId);
		profilePhotoInput.value = photoId;
	}
	this.classList.add('profilePicture');
}

const select = document.querySelector('select[name=lists]');
const addButton = document.getElementById('addToList');
if (addButton != null) {
	addButton.addEventListener('click', function () {
		const petId = document.querySelector('.petProfile').dataset.id;
		sendPostRequest(getRootUrl() + "/api/pet", { petId: petId, listId: select.value, csrf: getCSRF() }, function () {
			const tempText = document.getElementById('tempText');
			let result;
			try {
				result = JSON.parse(this.responseText);
				if (result['value'] == true) {
					tempText.innerHTML = 'Added successfully';
					tempText.style.color = 'green';
					setTimeout(function () { tempText.innerHTML = ''; }, 3000);
				}
			} catch (error) {
				tempText.innerHTML = 'Already on list';
				tempText.style.color = 'red';
				setTimeout(function () { tempText.innerHTML = ''; }, 3000);
			}
		});
	});
}