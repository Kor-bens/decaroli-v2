<?php 

class Image {
    protected int       $id_image;
    protected string    $nom_image;
    protected string    $url;
    protected Page      $id_page;

    public function __construct($id_image,$nom_image,$url){
        $this-> id_image        = $id_image;
        $this-> nom_image       = $nom_image;
        $this-> url             = $url;
    }

    public function getIdImage(): int {
        return $this->id_image;
    }
    public function setIdImage(int $id_image) {
        $this->id_image = $id_image;
    }

    public function getNomImage(): string {
        return $this->nom_image;
    }
    public function setNomImage(string $nom_image) {
        $this->nom_image = $nom_image;
    }

    public function getUrl(): string {
        return $this->url;
    }
    public function setUrl(string $url) {
        $this->url = $url;
    }

   
    public function getIdPage(): Page {
        return $this->id_page;
    }
    public function setIdPage(Page $id_page) {
        $this->id_page = $id_page;
    }
}