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



function showSelection(editForm, inputField) {
	editForm.style.display = "flex";
	inputField.style.display = "none";
}

function confirmSelection(editForm, inputField) {
	const field = this.attributes['name'].value.split('Confirm')[0];

	if (field === 'colorRaceLocation') {
		changeColorRaceLocation(editForm, inputField);
	} else if (field === 'name') {
		changeNameAge(editForm, inputField);
	} else if (field === 'description') {
		changeDescription(editForm, inputField);
	}
}

function changeNameAge(editForm, inputField) {
	const name = document.querySelector('#nameInput').value;
	const petId = document.querySelector('.petProfile').dataset.id;
	let string = inputField.querySelector('h3').innerHTML.split(',');
	sendPostRequest(getRootUrl() + '/control/api/pet.php', { field: 'name', value: name, petId: petId },
		function () {
			const result = JSON.parse(this.responseText);
			if (result.value === true) {
				editForm.style.display = 'none';
				inputField.style.display = 'flex';
				if (name == '') {
					if (string.length === 2)
						inputField.querySelector('h3').innerHTML = string[1];
					else
						inputField.querySelector('h3').innerHTML = string[0];
				} else {
					if (string.length === 2)
						inputField.querySelector('h3').innerHTML = name + ', ' + string[1];
					else
						inputField.querySelector('h3').innerHTML = name + ', ' + string[0];
				}
			}
		}
	);
}

function changeColorRaceLocation(editForm, inputField) {
	const color = document.querySelector('#colorInput').value;
	const species = document.querySelector('#raceInput').value;
	const location = document.querySelector('#locationInput').value;

	if (color != '' && species != '' && location != '') {
		const petId = document.querySelector('.petProfile').dataset.id;
		sendPostRequest(getRootUrl() + "/control/api/pet.php", { field: 'color', value: color, petId: petId },
			function () {
				const result = JSON.parse(this.responseText);
				if (result.value === true) {
					sendPostRequest(getRootUrl() + "/control/api/pet.php", { field: 'species', value: species, petId: petId },
						function () {
							const result = JSON.parse(this.responseText);
							if (result.value === true) {
								sendPostRequest(getRootUrl() + "/control/api/pet.php", { field: 'location', value: location, petId: petId },
									function () {
										if (result.value === true) {
											editForm.style.display = 'none';
											inputField.style.display = 'flex';
											inputField.querySelector('h4').innerHTML = color + ' ' + species + ', ' + location;
										}
									}
								);
							}
						}
					);
				}
			}
		);
	}
}

function changeDescription(editForm, inputField) {
	const description = document.querySelector('#descriptionInput').value;
	const petId = document.querySelector('.petProfile').dataset.id;

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

function resetSelection(editForm, inputField) {
	editForm.style.display = "none";

	inputField.style.display = "flex";
}

function editPet() {
	const editButtons = Array.from(document.getElementsByClassName('edit'));

	editButtons.forEach(editButton => {
		editButton.style.display = "flex";
	});

	editPetButton.style.display = 'none';
	closeEditPetButton.style.display = 'inline';
}

function closeEditPet() {
	const editButtons = Array.from(document.getElementsByClassName('edit'));

	editButtons.forEach(editButton => {
		console.log(editButton);
		editButton.style.display = "none";
	});

	let forms = document.querySelectorAll(".textButtonPair form");
	let editFields = document.querySelectorAll(".textButtonPair .edit");
	let initialFields = document.querySelectorAll(".textButtonPair > div");

	editFields.forEach(field => field.style.display = "none");
	forms.forEach(form => form.style.display = "none");
	initialFields.forEach(field => field.id == "bio" ? field.style.display = "flex" : field.style.display = "block");

	editPetButton.style.display = 'inline';
	closeEditPetButton.style.display = 'none';
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