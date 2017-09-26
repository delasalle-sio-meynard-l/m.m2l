<?php
// Projet Réservations M2L - version web mobile
// fichier : 
// Rôle : 
// Création : 
// Mise à jour : 

class Salle
{
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------- Membres privés de la classe ---------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    private $id;
    private $room_name;
    private $capacity;
    private $area_name;
    
    // ------------------------------------------------------------------------------------------------------
    // ----------------------------------------- Constructeur -----------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function Salle($unId, $uneRoomName, $uneCapacity, $uneAreaName) {
        $this->id = $unId;
        $this->room_name = $uneRoomName;
        $this->capacity = $uneCapacity;
        $this->area_name = $uneAreaName;
    }
    
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- Getters et Setters ------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function getId() {return $this->id;}
    public function setId($unId) {$this->id = $unId;}
    
    public function getRoom_name() {return $this->room_name;}
    public function setRoom_name($uneRoomName) {$this->room_name = $uneRoomName;}
    
    public function getCapacity() {return $this->capacity;}
    public function setCapacity($uneCapacity) {$this->room_name = $uneCapacity;}
    
    public function getAreaName() {return $this->area_name;}
    public function setAreaName($uneAreaName) {$this->room_name = $uneAreaName;}
    
    // ------------------------------------------------------------------------------------------------------
    // -------------------------------------- Méthodes d'instances ------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function toString(){
        $msg = "Salle : <br>";
        $msg .= "id : ".$this->getId()."<br>";
        $msg .= "room_name : ".$this->getRoom_name()."<br>";
        $msg .= "capacity : ".$this->getCapacity()."<br>";
        $msg .= "area_name : ".$this->getArea_name()."<br>";
        return $msg;
    }
}







// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces après la balise de fin de script !!!!!!!!!!!!