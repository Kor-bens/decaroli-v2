<?php

namespace DECAROLI\dao;

use DECAROLI\dao\Db;
use DECAROLI\dao\Requete;
use DECAROLI\models\Utilisateur;
use DECAROLI\models\Role;
use DECAROLI\models\Page;
use DECAROLI\models\Image;
use \PDO;
use \PDOException;
// Class pour intéragir avec la base de donnée
class DaoAppli
{
    private PDO $db;
    // Initialisation de la connexion à la base de données et configuration des attributs PDO.
    public function __construct()
    {
        $dbObjet  =  new Db;
        $this->db = $dbObjet->getDb();
        // Activez le mode d'erreur PDO
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    //Récupération d'un utilisateur de la base de données en fonction de son identifiant (nom ou mail).
    //Si un utilisateur est trouvé, un objet Role est crée et un objet Utilisateur est crée et retourné.
    public function recuperationUser($identifiant): ?Utilisateur
    {
        try {
            $requete = Requete::REQ_USER;
            $stmt = $this->db->prepare($requete);
            $stmt->bindValue(':identifiant', $identifiant, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
                $role = new Role($resultat['id_role'], $resultat['nom_role']);
                return new Utilisateur($resultat['id_utilisateur'], $resultat['nom'], $resultat['mail'], $resultat['mdp'], $role);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }



    public function ajoutUtilisateur($nom, $mail, $mdp , $id_role)
    {
        try {

            $requete = Requete::REQ_AJOUT_USER;
            $stmt = $this->db->prepare($requete);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
            $stmt->bindParam(':id_role', $id_role, PDO::PARAM_INT);
            $stmt->execute();
            $idDernierUtilisateur = $this->db->lastInsertId();
            return $idDernierUtilisateur;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }
    public function recuperatioRole($nomRole)
    {
        $requete = Requete::REQ_ROLE;
        $stmt = $this->db->prepare($requete);
        $stmt->bindParam(':nom', $nomRole, PDO::PARAM_STR);
        $stmt->execute();
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        $role = new Role($resultat['id_role'], $resultat['nom_role']);
        return $role;
    }



    public function modifBackgroundTitre(
        $nouveauTitre,
        $nouveauBackground,
        $nouvelleCouleurTitre,
        $nouvelleFontFamily,
        $nouvelleFontGrand,
        $nouvelleFontSizeMoyen,
        $nouvelleFontSizePetit
    ) {
        $requete = Requete::REQ_MODIF_BACKGROUND;
        try {
            $stmt = $this->db->prepare($requete);
            $stmt->bindParam(':background', $nouveauBackground);
            $stmt->bindParam(':titre', $nouveauTitre);
            $stmt->bindParam(':titre_color', $nouvelleCouleurTitre);
            $stmt->bindParam(':titre_font_family', $nouvelleFontFamily);
            $stmt->bindParam(':titre_font_size_grand_ecran', $nouvelleFontGrand);
            $stmt->bindParam(':titre_font_size_moyen_ecran', $nouvelleFontSizeMoyen);
            $stmt->bindParam(':titre_font_size_petit_ecran', $nouvelleFontSizePetit);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public function getDetailPage()
    {
        $requete = Requete::REQ_DETAIL_PAGE_PROMOTION;
        try {
            $stmt = $this->db->query($requete);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
        //Récuperation des données 
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$resultat) {
            return null;
        }
        //Objet page instancié en passant les données au constructeur
        $page = new Page(
            $resultat['id_page'],
            $resultat['titre'],
            $resultat['titre_font_size_grand_ecran'],
            $resultat['titre_font_size_moyen_ecran'],
            $resultat['titre_font_size_petit_ecran'],
            $resultat['titre_font_family'],
            $resultat['titre_color'],
            $resultat['bkgd_color']
        );
        return $page;
    }

    public function getDetailPageEtImages()
    {
        $page = $this->getDetailPage();

        $requete = Requete::REQ_PAGE_IMAGES;
        try {
            $stmt = $this->db->query($requete);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
        $images = [];
        while ($resultat_image = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // var_dump($resultat_image);
            $image = new Image(
                $resultat_image['id_image'],
                $resultat_image['nom_image'],
                $resultat_image['url']
            );
            $image->setIdPage($page);
            array_push($images, $image);
        }
        return ['page' => $page, 'images' => $images];
    }

    public function traitementImage($nomUnique, $nomFichier, $idPage)
    {
        $requete = Requete::REQ_AJOUT_IMAGE;

        try {
            $stmt = $this->db->prepare($requete);
            $stmt->bindParam(':url', $nomUnique, PDO::PARAM_STR);
            $stmt->bindParam(':nom_image', $nomFichier, PDO::PARAM_STR);
            $stmt->bindParam(':id_page', $idPage, PDO::PARAM_INT);
            $stmt->execute();

            // Récupére l'ID de l'image insérée
            $idImage = $this->db->lastInsertId();

            // Création d'une nouvelle instance de la classe Image
            $image = new Image($idImage, $nomFichier, $nomUnique);

            return $image;
        } catch (PDOException $e) {
            // Erreur
            error_log($e->getMessage());
            return null;
        }
    }

    public function supprimerImage($idImage)
    {
        $requete = Requete::REQ_SUPPR_IMAGE;

        try {
            $stmt = $this->db->prepare($requete);
            $stmt->bindParam(':idImage', $idImage, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            // Erreur
            error_log($e->getMessage());
        }
    }

    public function modifierImage($nouveauNomImage, $nouvelleUrl, $idImageModifier)
    {
        $requete = Requete::REQ_MODIF_IMAGE;
        try {
            $stmt = $this->db->prepare($requete);
            $stmt->bindParam(':nom_image', $nouveauNomImage, PDO::PARAM_STR);
            $stmt->bindParam(':url', $nouvelleUrl, PDO::PARAM_STR);
            $stmt->bindParam(':id_image', $idImageModifier, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            // Erreur
            error_log($e->getMessage());
            return false;
        }
    }

    public function getNomFichierImageById($idImage)
    {
        $requete = Requete::REQ_NOM_IMAGE_ID;
        try {
            $stmt = $this->db->prepare($requete);
            $stmt->bindParam(':id', $idImage, PDO::PARAM_INT);
            $stmt->execute();
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
            return isset($resultat['url']) ? $resultat['url'] : null;
        } catch (PDOException $e) {
            // Erreur
            error_log($e->getMessage());
            return null;
        }
    }

    public function getRoles()
    {
        $requete = Requete::REQ_NOM_ROLE;
        try {
            $stmt = $this->db->query($requete);
            $stmt->execute();
            $roles = array();
            while ($resultat = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $role = new Role($resultat['id_role'], $resultat['nom_role']);
                array_push($roles, $role);
            };
            
            return $roles;
        } catch (PDOException $e) {
            // Erreur
            error_log($e->getMessage());
            return null;
        }
    }

    public function getUserDashboard(){
        $requete = Requete::REQ_USER_DASHBOARD;
        try {
            $stmt = $this->db->query($requete);
            $stmt->execute();
            $users = array();
            while ($resultat = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $role = new Role($resultat['id_role'], $resultat['nom_role']);
                $user = new Utilisateur($resultat['id_utilisateur'], $resultat['nom'], $resultat['mail'], $resultat['mdp'], $role);
                array_push($users, $user);  
            };

            return $users;
        } catch (PDOException $e) {
            // Erreur
            error_log($e->getMessage());
            return null;
        }
    }

    public function supprimerUser($idUtilisateur)
    {
        $requete = Requete::REQ_SUPPR_USER;

        try {
            $stmt = $this->db->prepare($requete);
            $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            // Erreur
            error_log($e->getMessage());
        }
    }

    public function modifierUserDao($nouveauNom, $nouveauMdp, $nouveauMail, $nouveauRole, $idUtilisateur)
    {
        $requete = Requete::REQ_MODIF_USER;
        try {
            $stmt = $this->db->prepare($requete);
            $stmt->bindParam(':nom', $nouveauNom, PDO::PARAM_STR);
            $stmt->bindParam(':mdp', $nouveauMdp, PDO::PARAM_STR);
            $stmt->bindParam(':mail', $nouveauMail, PDO::PARAM_STR);
            $stmt->bindParam(':id_role', $nouveauRole, PDO::PARAM_STR);
            $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            // Erreur
            error_log($e->getMessage());
            return false;
        }
    }
}
