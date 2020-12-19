<?php

require_once(dirname(__FILE__). '/../session.php');
require_once(dirname(__FILE__). '/user.php');
require_once(dirname(__FILE__). '/../db.php');
require_once(dirname(__FILE__). '/pet.php');

$petId = $_POST['petId'];
$comment = $_POST['comment'];
$userId = Session\getAuthenticatedUser()['id'];

if ($petId != null && $comment != null) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('INSERT INTO Post(petId, userId, description, postDate, answerToPostID) VALUES(?, ?, ?, ?, ?)');
    $stmt->execute(array($petId, $userId, $comment, date('Y-m-d H:i:s'), null));


    $post = API\getLastPost($petId);
    echo json_encode($post);
}

?>