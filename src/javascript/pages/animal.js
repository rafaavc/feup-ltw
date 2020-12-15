import SimpleSlider from '../slider.js'
import { sendPostRequest } from '../ajax.js'
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


let forms = Array.from(document.getElementsByClassName('textButtonPair'));
forms.forEach(form => {
	let inputField = form.children[0];
	let editForm = form.children[1];

	let edit = inputField.getElementsByClassName("edit");
	edit[0].addEventListener('click', () => {
		showSelection(editForm, inputField);
	});

	let confirm = Array.from(editForm.getElementsByClassName("confirm"));
	confirm.forEach(confirmButton => confirmButton.addEventListener('click', function (event) {
		event.preventDefault();
		confirmSelection.bind(this)(editForm, inputField);
	}));

	let close = editForm.getElementsByClassName("close");
	close[0].addEventListener('click', (event) => {
		event.preventDefault();
		resetSelection(editForm, inputField);
	});

});

let photos = document.getElementById('photosInput');



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

function changeNameAge(editForm, inputField) {
	const name = document.querySelector('#nameInput').value;
	const birthdate = document.querySelector('#birthdateInput').value;
	const petId = document.querySelector('.petProfile').dataset.id;
	sendPostRequest(getRootUrl() + '/control/api/pet.php', { field: 'nameAge', value: name, birthdate: birthdate, petId: petId },
		function () {
			const result = JSON.parse(this.responseText);
			if (result.value === true) {
				editForm.style.display = 'none';
				inputField.style.display = 'flex';
				const today = new Date();
				const date = new Date(birthdate);
				const years = today.getFullYear() - date.getFullYear();
				const months = today.getMonth() - date.getMonth();
				let age;
				console.log(years, months);
				if (years > 1) {
					age = years.toString() + ' years';
					if (months > 1) {
						age += ' and ' + months.toString() + ' months';
					}
					else if (months === 1) {
						age += ' and ' + months.toString() + ' month';
					}
				} else if (years === 1) {
					age = years.toString() + 'year ';
					if (months > 1) {
						age += ' and ' + months.toString() + ' months';
					}
					else if (months === 1) {
						age += ' and ' + months.toString() + ' month';
					}
				} else if (months > 1) {
					age = months.toString() + ' months';
				}
				else if (months === 1) {
					age = months.toString() + ' month';
				} else {
					age = "0 months";
				}
				if (name == '') {
					inputField.querySelector('h3').innerHTML = age;
				} else {
					inputField.querySelector('h3').innerHTML = name + ', ' + age;
				}
			}
		}
	);
}

function changeColorSpecieRaceLocation(editForm, inputField) {
	const color = document.querySelector('#colorInput').value;
	const species = document.querySelector('#specieInput').value;
	const race = document.querySelector('#raceInput').value;
	const location = document.querySelector('#locationInput').value;

	if (color != '' && (species != '' || race != '') && location != '') {
		const petId = document.querySelector('.petProfile').dataset.id;
		sendPostRequest(getRootUrl() + "/control/api/pet.php", { field: 'colorSpeciesRaceLocation', color: color, species: species, race: race, location: location, petId: petId },
			function () {
				const result = JSON.parse(this.responseText);
				if (result.value === true) {
					editForm.style.display = 'none';
					inputField.style.display = 'flex';
					const string = race == '' ? species : race;
					inputField.querySelector('h4').innerHTML = color + ' ' + string + ', ' + location;

				}
			}
		);
	}

}

function changeDescription(editForm, inputField) {
	const description = document.querySelector('#descriptionInput').value;
	const petId = document.querySelector('.petProfile').dataset.id;

	if (description != '') {
		sendPostRequest(getRootUrl() + '/control/api/pet.php', { field: 'description', value: description, petId: petId },
			function () {
				const result = JSON.parse(this.responseText);
				if (result.value === true) {
					editForm.style.display = 'none';
					inputField.style.display = 'flex';
					inputField.querySelector('p').innerHTML = description;
				}
			}
		);
	}
}

function resetSelection(editForm, inputField) {
	editForm.style.display = "none";

	inputField.style.display = "flex";
}

function editPet() {
	const editButtons = Array.from(document.getElementsByClassName('edit'));

	editButtons.forEach(editButton => {
		editButton.style.display = "flex";
	});

	const photos = document.getElementById('photosInput');
	photos.style.display = 'flex';

	editPetButton.style.display = 'none';
	closeEditPetButton.style.display = 'inline';
}

function closeEditPet() {
	const editButtons = Array.from(document.getElementsByClassName('edit'));

	editButtons.forEach(editButton => {
		editButton.style.display = "none";
	});

	const photos = document.getElementById('photosInput');
	photos.style.display = 'none';

	let forms = document.querySelectorAll(".textButtonPair form");
	let editFields = document.querySelectorAll(".textButtonPair .edit");
	let initialFields = document.querySelectorAll(".textButtonPair > div");

	editFields.forEach(field => field.style.display = "none");
	forms.forEach(form => form.style.display = "none");
	initialFields.forEach(field => field.id == "bio" ? field.style.display = "flex" : field.style.display = "block");

	editPetButton.style.display = 'inline';
	closeEditPetButton.style.display = 'none';
	sendPostRequest(getRootUrl() + "/control/api/post.php", { petId: petId, comment: comment }, receiveComment);
}

function submitComment(event) {
	event.preventDefault();

	const petId = document.querySelector('.petProfile').dataset.id;
	const comment = document.querySelector('form textarea').value;

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

	sendPostRequest(getRootUrl() + "/control/api/proposeToAdopt.php", { petId: petId, value: 'adopt' }, changeAdoptButton);
}

function changeAdoptButton() {
	const proposeToAdopt = JSON.parse(this.responseText);

	if (proposeToAdopt.length === 1) {
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
}

function cancelProposeToAdoptPet(event) {
	event.preventDefault();

	const petId = document.querySelector('.petProfile').attributes['data-id'].value;

	sendPostRequest(getRootUrl() + "/control/api/proposeToAdopt.php", { petId: petId, value: 'cancel' }, changeCancelButton);
}

function changeCancelButton(event) {
	const proposeToAdopt = JSON.parse(this.responseText);

	if (proposeToAdopt.length === 0) {
		document.querySelector('.petProfile footer > p').remove();
		const button = document.createElement('button');

		button.id = "adopt";
		button.className = "simpleButton contrastButton";
		button.innerHTML = "Adopt";
		button.addEventListener('click', proposeToAdoptPet);

		document.querySelector('.petProfile footer').appendChild(button);
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

/*
const removeButtons = Array.from(document.getElementsByClassName('remove'));
removeButtons.forEach(removeButton => {
	removeButton.addEventListener('click', removeImage);
});

function removeImage() {
	const lastButton = fileInputButtons[fileInputButtons.length - 1].obj;
	const lastButtonId = fileInputButtons[fileInputButtons.length - 1].id;
	const buttonIdx = fileInputButtons.findIndex((el) => el.id === lastButtonId);
	if (profilePhotoInput.value === lastButton.files[0].name) {
		profilePhotoInput.value = '';
	}
	fileInputButtons[buttonIdx].obj.remove();
	this.parentNode.remove();

	fileInputButtons.splice(buttonIdx, 1);
}
*/

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


function updateProfilePic() {
	const prevProfileImage = document.querySelectorAll('img.profilePicture');
	prevProfileImage.forEach((pi) => pi.classList.remove('profilePicture'));
	this.classList.add('profilePicture');
	const buttonId = parseInt(this.dataset.buttonId);
	const button = fileInputButtons.find((button) => button.id === buttonId).obj;
	profilePhotoInput.value = button.files[0].name;
}