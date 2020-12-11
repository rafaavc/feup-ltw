<?php


$petId = $_POST['petId'];
$userId = $_POST['userId'];
$comment = $_POST['comment'];

include_once(dirname(__FILE__). '/../db.php');
include_once(dirname(__FILE__). '/pet.php');

$db = Database::instance()->db();
$stmt = $db->prepare('INSERT INTO Post(petId, userId, description, postDate, answerToPostID) VALUES(?, ?, ?, ?, ?)');
$stmt->execute(array($petId, $userId, $comment, date('Y-m-d H:i:s'), null));


$post = API\getLastPost($petId);
echo json_encode($post);

?>