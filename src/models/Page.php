<?php

class Page
{
    protected int       $id_page;
    protected string    $titre;
    protected string    $bkgd_color;
    protected Admin       $id_admin;

    public function __construct($id_page, $titre, $bkgd_color, $id_admin)
    {
        $this->$id_page   = $id_page;
        $this->titre      = $titre;
        $this->bkgd_color = $bkgd_color;
        $this->id_admin   = $id_admin;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }
    public function setTitre(string $titre)
    {
        $this->titre = $titre;
    }

    public function getBkgdColor(): string
    {
        return $this->bkgd_color;
    }
    public function setBkgdColor(string $bkgd_color)
    {
        $this->bkgd_color = $bkgd_color;
    }

    public function getIdAdmin()
    {
        return $this->id_admin;
    }
    public function setIdAdmin(Admin $id_admin)
    {
        $this->id_admin = $id_admin;
    }
}
