<?php
namespace DECAROLI\models;

class Role{
    public int $id_role;
    public string $nom_role;

         public function __construct($id_role, $nom_role){
          $this->id_role = $id_role;
          $this->nom_role = $nom_role; 
         }

}