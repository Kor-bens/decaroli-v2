<?php
namespace DECAROLI\models;

class Role{
    public int $id_role;
    public string $nom_role;

         public function __construct($id_role = "", $nom_role){
          $this->id_role = $id_role;
          $this->nom_role = $nom_role; 
         }


    public function getIdRole(): int {
        return $this->id_role;
    }
    public function setIdRole(int $id_role) {
        $this->id_role = $id_role;
    }

    public function getNomRole(): string {
        return $this->nom_role;
    }
    public function setNomRole(string $nom_role) {
        $this->nom_role = $nom_role;
    }
}