<?php
namespace DECAROLI\models;
class Utilisateur
{
    protected int       $id_utilisateur;
    protected string    $nom;
    protected string    $mail;
    protected string    $mdp;
    protected Role      $role;

    public function __construct($id_utilisateur, $nom, $mail, $mdp, $role)
    {
        $this-> id_utilisateur        = $id_utilisateur;
        $this-> nom             = $nom;
        $this-> mail            = $mail;
        $this-> mdp             = $mdp;
        $this->role             = $role;
    }


    public function getIdUtilisateur(): int
    {
        return $this->id_utilisateur;
    }
    public function setIdUtilisateur(int $id_utilisateur)
    {
        $this->id_utilisateur = $id_utilisateur;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom)
    {
        $this->nom = $nom;
    }

    public function getMdp(): string
    {
        return $this->mdp;
    }

    public function getMail(): string {
        return $this->mail;
    }
    public function setMail(string $mail) {
        $this->mail = $mail;
    }

    public function getIdRole(): Role {
        return $this->role; 
    }
  
}
