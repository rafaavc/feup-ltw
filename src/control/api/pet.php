<?php

namespace API;
use Router;
use Database;

function getPets() {
    $stmt = Database::db()->prepare("SELECT * FROM Pet ORDER BY datePosted DESC");
    $stmt->execute();
    return $stmt;
}

function getSpecies() {
    $stmt = Database::db()->prepare("SELECT * FROM PetSpecie ORDER BY name");
    $stmt->execute();
    return $stmt;
}


?>