let commentForm = document.querySelector('#comments > form');

commentForm.addEventListener('submit', function (event) {
	submitComment(event);
});

function submitComment(event) {
	event.preventDefault(event);

	let petId = document.querySelector('.petProfile').attributes['data-id'].value;
	let comment = document.querySelector('form textarea').value;

	if (comment.length < 1) return;

	let request = new XMLHttpRequest();
	request.addEventListener('load', receiveComment);
	request.open("post", "/control/api/post.php", true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send(encodeForAjax({ petId: petId, comment: comment }));
}

function receiveComment() {
	let post = JSON.parse(this.responseText);

	let article = document.createElement('article');
	article.className = 'comment';

	let image = document.createElement('img');
	image.src = '../../images/userProfilePictures/' + post.userId + '.jpg';

	let paragraph = document.createElement('p');
	paragraph.innerHTML = post.description;

	let user = document.createElement('span');
	user.className = 'user';
	user.innerHTML = post.shortUserName;

	let date = document.createElement('span');
	date.className = 'date';
	date.innerHTML = post.postDate;

	article.append(image);
	article.append(paragraph);
	article.append(user);
	article.append(date);

	document.querySelector('#comments').insertBefore(article, commentForm);
}