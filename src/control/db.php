<?php
  /**
   * A singleton representing the app connection
   * to the database.
   */
  class Database {
    private static $instance = NULL;
    private $db = NULL;

    /**
     * Private constructor. Creates a database connection.
     * Sets fetch mode to fetch using associative arrays
     * and turns on exceptions. Also turns on foreign keys
     * enforcement.
     */
    private function __construct() {
      $this->db = new PDO('sqlite:'.dirname(__FILE__).'/../database/toTheRescue.db');
      $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->db->query('PRAGMA foreign_keys = ON');
      if (NULL == $this->db)
        throw new Exception("Failed to open database");
    }

    /**
     * Returns the database connection.
     */
    public static function db() {
        return self::instance()->db;
    }

    /**
     * Returns this singleton instance. Creates it if needed.
     */
    static function instance() {
      if (NULL == self::$instance) {
        self::$instance = new Database();
      }
      return self::$instance;
    }
  }

  function getPet($petId){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT * FROM Pet WHERE id=?');
    $stmt->execute(array($petId));
    return $stmt->fetch();
  }

  function getAllPets(){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT * FROM Pet');
    $stmt->execute(array());
    return $stmt->fetchAll();
  }

  function getSpecie($specieId){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT name FROM PetSpecie WHERE id=?');
    $stmt->execute(array($specieId));
    return $stmt->fetch();
  }

  function getRace($raceId){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT name FROM PetRace WHERE id=?');
    $stmt->execute(array($raceId));
    return $stmt->fetch();
  }

  function getColor($colorId){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT name FROM PetColor WHERE id=?');
    $stmt->execute(array($colorId));
    return $stmt->fetch();
  }

  function getPetPhotos($petId){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT photoId FROM PetPhoto WHERE petId=?');
    $stmt->execute(array($petId));
    return $stmt->fetchAll();
  }

  function getPosts($petId){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT * FROM Post WHERE petId=?');
    $stmt->execute(array($petId));
    return $stmt->fetchAll();
  }

  function getUser($userId){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT * FROM User WHERE id=?');
    $stmt->execute(array($userId));
    return $stmt->fetch();
  }

  function getUserPets($userId){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT * FROM Pet WHERE userId=?');
    $stmt->execute(array($userId));
    return $stmt->fetchAll();
  }

  function getUserLists($userId){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT * FROM List WHERE userId=?');
    $stmt->execute(array($userId));
    return $stmt->fetchAll();
  }
?>
