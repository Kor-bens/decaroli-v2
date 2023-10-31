<?php

class Admin
{
    protected int       $id_admin;
    protected string    $nom;
    protected string    $mail;
    protected string    $mdp;

    public function __construct($id_admin, $nom, $mail, $mdp)
    {
        $this-> id_admin        = $id_admin;
        $this-> nom             = $nom;
        $this-> mail            = $mail;
        $this-> mdp             = $mdp;
    }


    public function getIdAdmin(): int
    {
        return $this->id_admin;
    }
    public function setIdAdmin(int $id_admin)
    {
        $this->id_admin = $id_admin;
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
}
