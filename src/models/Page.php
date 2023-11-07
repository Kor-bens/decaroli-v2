<?php

namespace DECAROLI\models;

use       DECAROLI\models\utilisateur;

class Page
{
    protected int       $id_page;
    protected string    $titre;
    protected string    $titre_font_size_grand_ecran;
    protected string    $titre_font_size_moyen_ecran;
    protected string    $titre_font_size_petit_ecran;
    protected string    $titre_font_family;
    protected string    $titre_color;
    protected string    $bkgd_color;
    protected utilisateur     $id_utilisateur;

    public function __construct($id_page, $titre, $titre_font_size_grand_ecran, $titre_font_size_moyen_ecran, $titre_font_size_petit_ecran, $titre_font_family, $titre_color, $bkgd_color)
    {
        $this->id_page                          = $id_page;
        $this->titre                            = $titre;
        $this->titre_font_size_grand_ecran      = $titre_font_size_grand_ecran;
        $this->titre_font_size_moyen_ecran      = $titre_font_size_moyen_ecran;
        $this->titre_font_size_petit_ecran      = $titre_font_size_petit_ecran;
        $this->titre_font_family                = $titre_font_family;
        $this->titre_color                      = $titre_color;
        $this->bkgd_color                       = $bkgd_color;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }
    public function setTitre(string $titre)
    {
        $this->titre = $titre;
    }

    public function getTitreFontSizeGrandEcran(): string
    {
        return $this->titre_font_size_grand_ecran;
    }
    public function setTitreFontSizeGrandEcran(string $titre_font_size_grand_ecran)
    {
        $this->titre_font_size_grand_ecran = $titre_font_size_grand_ecran;
    }

    public function getTitreFontSizeMoyenEcran(): string
    {
        return $this->titre_font_size_moyen_ecran;
    }
    public function setTitreFontSizeMoyenEcran(string $titre_font_size_moyen_ecran)
    {
        $this->titre_font_size_moyen_ecran = $titre_font_size_moyen_ecran;
    }

    public function getTitreFontSizePetitEcran(): string
    {
        return $this->titre_font_size_petit_ecran;
    }
    public function setTitreFontSizePetitEcran(string $titre_font_size_petit_ecran)
    {
        $this->titre_font_size_petit_ecran = $titre_font_size_petit_ecran;
    }

    public function getTitreFontFamily(): string
    {
        // return isset($this->titre_font_family) ? $this->titre_font_family : 'Arial';
        return $this->titre_font_family;
    }
    public function setTitreFontFamily(string $titre_font_family)
    {
        $this->titre_font_family = $titre_font_family;
    }

    public function getTitreColor(): string
    {
        return $this->titre_color;
    }
    public function setTitreColor(string $titre_color)
    {
        $this->titre_color = $titre_color;
    }

    public function getBkgdColor(): string
    {
        return $this->bkgd_color;
    }
    public function setBkgdColor(string $bkgd_color)
    {
        $this->bkgd_color = $bkgd_color;
    }
    public function getIdUtilisateur()
    {
        return $this->id_utilisateur;
    }
    public function setIdUtilisateur(utilisateur $id_utilisateur)
    {
        $this->id_utilisateur = $id_utilisateur;
    }
}
