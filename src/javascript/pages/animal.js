import SimpleSlider from '../slider.js'
import { sendPostRequest, sendDeleteRequest } from '../ajax.js'
import './generic.js'
import { getRootUrl } from '../init.js'
import { elapsedTime } from '../utils.js'

const commentForm = document.querySelector('#comments > form');
const adoptButton = document.querySelector('#adopt');
const cancelButton = document.querySelector('#cancel');
const editPetButton = document.getElementById('editPet');
const closeEditPetButton = document.getElementById('closeEditPet');

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
if (closeEditPetButton != null) {
	closeEditPetButton.addEventListener('click', closeEditPet);
}




function showSelection(editForm, inputField) {
	editForm.style.display = "flex";
	inputField.style.display = "none";
}

function confirmSelection(editForm, inputField) {
	const field = this.attributes['name'].value.split('Confirm')[0];

	if (field === 'colorSpeciesRaceLocation') {
		changeColorSpecieRaceLocation(editForm, inputField);
	} else if (field === 'name') {
		changeNameAge(editForm, inputField);
	} else if (field === 'description') {
		changeDescription(editForm, inputField);
	}
}

function resetSelection(editForm, inputField) {
	editForm.style.display = "none";

	inputField.style.display = "flex";
}

function editPet() {
	const form = document.getElementById('updateForm');
	form.style.display = 'grid';

	const info = document.getElementById('petInfo');
	info.style.display = 'none';

	const photos = document.getElementById('photosInput');
	photos.style.display = 'flex';

	editPetButton.style.display = 'none';
	closeEditPetButton.style.display = 'inline';
}

function closeEditPet() {
	const photos = document.getElementById('photosInput');
	photos.style.display = 'none';

	const form = document.getElementById('updateForm');
	form.style.display = 'none';

	const info = document.getElementById('petInfo');
	info.style.display = 'initial';

	editPetButton.style.display = 'inline';
	closeEditPetButton.style.display = 'none';
	sendPostRequest(getRootUrl() + "/control/api/post.php", { petId: petId, comment: comment }, receiveComment);
}

function submitComment(event) {
	event.preventDefault();

	const petId = document.querySelector('.petProfile').dataset.id;
	const comment = document.getElementById('commentInput').value;

	if (comment.length < 1) return;

	sendPostRequest(getRootUrl() + "/control/api/post.php", { petId: petId, comment: comment }, receiveComment);
}

function receiveComment() {
	const post = JSON.parse(this.responseText);

	const article = document.createElement('article');
	article.className = 'comment';
	article.id = `post-${post.id}`;

	const image = document.createElement('div');
	image.className = 'image';
	image.setAttribute('style', "background-image: url('../../images/userProfilePictures/" + post.userId + ".jpg'");


	const paragraph = document.createElement('p');
	paragraph.innerHTML = post.description;

	const user = document.createElement('span');
	user.className = 'user';
	user.innerHTML = post.shortUserName;

	const date = document.createElement('span');
	date.className = 'date';
	date.innerHTML = elapsedTime(post.postDate) + " ago";

	article.append(image);
	article.append(paragraph);
	article.append(user);
	article.append(date);

	document.querySelector('#comments').insertBefore(article, commentForm);
}

const slider = new SimpleSlider("mySlider", 3000, "30vw");
slider.start();


function proposeToAdoptPet(event) {
	event.preventDefault(event);

	const petId = document.querySelector('.petProfile').attributes['data-id'].value;

	sendPostRequest(getRootUrl() + "/api/adoption/" + petId, {}, changeAdoptButton);
}

function changeAdoptButton() {
	//const proposeToAdopt = JSON.parse(this.responseText);

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
}

function cancelProposeToAdoptPet(event) {
	event.preventDefault();

	const petId = document.querySelector('.petProfile').attributes['data-id'].value;

	sendDeleteRequest(`${getRootUrl()}/api/adoption/${petId}`, changeCancelButton);
}

function changeCancelButton(event) {
	//const proposeToAdopt = JSON.parse(this.responseText);

	document.querySelector('.petProfile footer > p').remove();
	const button = document.createElement('button');

	button.id = "adopt";
	button.className = "simpleButton contrastButton";
	button.innerHTML = "Adopt";
	button.addEventListener('click', proposeToAdoptPet);

	document.querySelector('.petProfile footer').appendChild(button);
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
		const photoId = this.attributes['data-id'].value;
		const photos = document.querySelector('#updateForm .photos');

		const photo = document.createElement('input');
		photo.name = 'removePhotos[]';
		photo.type = 'hidden';
		photo.value = photoId;
		photos.appendChild(photo);

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
			if (profilePhotoInput.value === lastButton.files[0].name) {
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
	const photos = document.querySelectorAll('#photosInput > .photos > div > img');
	photos.forEach((photo) => {
		if (photo.attributes['data-button-id'].value === profilePhotoInput.value) {
			photo.style.border = 'none';
		}

	})

	profilePhotoInput.value = this.attributes['data-button-id'].value;
	this.style.border = 'solid 0.3rem var(--accentColorDarker)';
}

const select = document.getElementById('selectList');
const addButton = document.getElementById('addToList');
addButton.addEventListener('click', function () {
	const petId = document.querySelector('.petProfile').dataset.id;
	const options = document.getElementsByClassName('listOption');
	sendPostRequest(getRootUrl() + "/control/api/pet.php", { petId: petId, listId: options[select.selectedIndex].innerHTML }, function () {
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