<?php

namespace API;

require_once(dirname(__FILE__)."/pet.php");
require_once(dirname(__FILE__)."/user.php");

function getPetDistance($pet, $value) {
    $dist = 0;
    $dist += similar_text(strtoupper($pet['name']), strtoupper($value));
    $dist += similar_text(strtoupper($pet['description']), strtoupper($value));
    return $dist;
}

function getUserDistance($pet, $value) {
    $dist = 0;
    $dist += similar_text(strtoupper($pet['name']), strtoupper($value));
    $dist += similar_text(strtoupper($pet['username']), strtoupper($value));
    $dist += similar_text(strtoupper($pet['description']), strtoupper($value));
    return $dist;
}

function searchPets($value) {
    $pets = getArrayFromSTMT(getPets(), true);
    usort($pets, function ($pet1, $pet2) use($value) {  
        if (!isset($pet1['distance'])) {
            $pet1['distance'] = getPetDistance($pet1, $value);  // the greater the better
        }
        if (!isset($pet2['distance'])) {
            $pet2['distance'] = getPetDistance($pet2, $value);
        }
        // orders in reverse
        return $pet1['distance'] > $pet2['distance'] ? -1 : ($pet1['distance'] < $pet2['distance'] ? 1 : 0);
    });
    return $pets;
}

function searchUsers($value) {
    $users = getArrayFromSTMT(getUsers(), true);
    usort($users, function ($user1, $user2) use($value) {  
        if (!isset($user1['distance'])) {
            $user1['distance'] = getPetDistance($user1, $value);  // the greater the better
        }
        if (!isset($user2['distance'])) {
            $user2['distance'] = getPetDistance($user2, $value);
        }
        // orders in reverse
        return $user1['distance'] > $user2['distance'] ? -1 : ($user1['distance'] < $user2['distance'] ? 1 : 0);
    });
    return $users;
}

function handleRequest() {
    $what = $GLOBALS['what'];
    $specie = $GLOBALS['specie'];
    $value = $GLOBALS['value'];

    $method = $_SERVER['REQUEST_METHOD'];
    if ($what == '-1' && $specie == '-1' && $method == 'GET') {
        responseJSON(array('pets' => searchPets($value), 'users' => searchUsers($value)));

    } /*else if ($type == 'mail' && $method == 'GET') {

        responseJSON(array('value' => emailExists($value)));

    }*/ else {
        http_response_code(400); // BAD REQUEST
        exit();
    }
}

if (isset($GLOBALS['what']) && isset($GLOBALS['specie']) && isset($GLOBALS['value'])) {
    handleRequest();
}

?>
