<?php

namespace API;
use Router;
use Database;

require_once(dirname(__FILE__)."/pet.php");
require_once(dirname(__FILE__)."/user.php");

/** 
 * This is quite stupid, but we couldn't understand how to use FTS5 with SQLite
 * in the time we had
 */
function getPetDistance($pet, $value) {
    $dist = 0;
    $dist += similar_text(strtoupper($pet['name']), strtoupper($value));
    $dist += similar_text(strtoupper($pet['description']), strtoupper($value));
    return $dist;
}

/** 
 * This is quite stupid, but we couldn't understand how to use FTS5 with SQLite
 * in the time we had
 */
function getUserDistance($pet, $value) {
    $dist = 0;
    $dist += similar_text(strtoupper($pet['name']), strtoupper($value));
    $dist += similar_text(strtoupper($pet['username']), strtoupper($value));
    $dist += similar_text(strtoupper($pet['description']), strtoupper($value));
    return $dist;
}

function searchPets($value, $query, $specie, $color, $size) {
    $stmt = Database::db()->prepare($query);
    if ($specie != -1) $stmt->bindParam(':specie', $specie);
    if ($color != -1) $stmt->bindParam(':color', $color);
    if ($size != -1) $stmt->bindParam(':size', $size);
    $stmt->execute();
    $pets = getArrayFromSTMT($stmt, true);
    if ($value == "") return $pets;
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
    $users = getArrayFromSTMT(getPublicUsers(), true);
    if ($value == "") return $users;
    usort($users, function ($user1, $user2) use($value) {  
        if (!isset($user1['distance'])) {
            $user1['distance'] = getUserDistance($user1, $value);  // the greater the better
        }
        if (!isset($user2['distance'])) {
            $user2['distance'] = getUserDistance($user2, $value);
        }
        // orders in reverse
        return $user1['distance'] > $user2['distance'] ? -1 : ($user1['distance'] < $user2['distance'] ? 1 : 0);
    });
    return $users;
}

function handleSearchRequest() {
    $what = $GLOBALS['what'];
    $specie = $GLOBALS['specie'];
    $color = $GLOBALS['color'];
    $size = $GLOBALS['size'];
    $value = $GLOBALS['value'];

    $specieRes = Database::db()->prepare("SELECT name FROM PetSpecie WHERE id = ?");
    $specieRes->execute(array($specie));
    $specieRes = $specieRes->fetch();
    $specie = $specieRes ? $specieRes['name'] : '-1';

    $colorRes = Database::db()->prepare("SELECT name FROM PetColor WHERE id = ?");
    $colorRes->execute(array($color));
    $colorRes = $colorRes->fetch();
    $color = $colorRes ? $colorRes['name'] : '-1';

    $sizeRes = Database::db()->prepare("SELECT name FROM PetSize WHERE id = ?");
    $sizeRes->execute(array($size));
    $sizeRes = $sizeRes->fetch();
    $size = $sizeRes ? $sizeRes['name'] : '-1';

    $queries = array();
    if ($specie != '-1') array_push($queries, "specie = :specie");
    if ($color != '-1') array_push($queries, "color = :color");
    if ($size != '-1') array_push($queries, "size = :size");

    $fullQuery = "";
    if (sizeof($queries) > 0) {
        $fullQuery = "SELECT * FROM ".$GLOBALS['petQuery']." WHERE ".join(" AND ", $queries);
    } else {
        $fullQuery = "SELECT * FROM ".$GLOBALS['petQuery'];
    }

    $method = $_SERVER['REQUEST_METHOD'];
    if ($what == '-1' && $method == 'GET') {
        responseJSON(array('pets' => searchPets($value, $fullQuery, $specie, $color, $size), 'users' => searchUsers($value)));

    } else if ($what == 'pet' && $method == 'GET') {

        responseJSON(array('pets' => searchPets($value, $fullQuery, $specie, $color, $size), 'users' => searchUsers($value)));

    } else if ($what == 'user' && $method == 'GET') {

        responseJSON(array('users' => searchUsers($value)));

    } else {
        Router\errorBadRequest();
    }
}

if (Router\isAPIRequest(__FILE__)) {
    // no need for csrf verification
    $parameters = getArrayParameters($GLOBALS, ['what', 'specie', 'color', 'size', 'value']);
    if ($parameters != null) handleSearchRequest();
    else Router\errorBadRequest();
}

?>
