import SimpleSlider from '../slider.js'
import { sendPostRequest } from '../ajax.js'
import './generic.js'
import { getRootUrl } from '../init.js'

let commentForm = document.querySelector('#comments > form');

commentForm.addEventListener('submit', submitComment);

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

	const image = document.createElement('img');
	image.src = '../../images/userProfilePictures/' + post.userId + '.jpg';

	const paragraph = document.createElement('p');
	paragraph.innerHTML = post.description;

	const user = document.createElement('span');
	user.className = 'user';
	user.innerHTML = post.shortUserName;

	const date = document.createElement('span');
	date.className = 'date';
	date.innerHTML = post.postDate;

	article.append(image);
	article.append(paragraph);
	article.append(user);
	article.append(date);

	document.querySelector('#comments').insertBefore(article, commentForm);
}

const slider = new SimpleSlider("mySlider", 3000, "30vw");
slider.start();
