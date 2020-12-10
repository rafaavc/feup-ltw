<?php

namespace API;
use Router;
use Database;

function getPets() {
    $stmt = Database::db()->prepare("SELECT Pet.id, userId, Pet.name, birthdate, description, datePosted, location, PetColor.name as color, PetSize.name as size, PetSpecie.name as specie, PetRace.name as race 
                                        FROM (((Pet JOIN PetColor on(Pet.color = PetColor.id)) JOIN PetSize ON(Pet.size = PetSize.id)) LEFT JOIN PetSpecie ON(Pet.specie = PetSpecie.id)) LEFT JOIN PetRace ON(Pet.race = PetRace.id) 
                                        ORDER BY datePosted DESC");
    $stmt->execute();
    return $stmt;
}

function getSpecies() {
    $stmt = Database::db()->prepare("SELECT * FROM PetSpecie ORDER BY name");
    $stmt->execute();
    return $stmt;
}


?>