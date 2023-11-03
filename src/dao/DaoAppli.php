<?php
namespace DECAROLI\dao;
use DECAROLI\controllers\Message;
use DECAROLI\controllers\CntrlAppli;
use DECAROLI\dao\Db;
use DECAROLI\dao\Requete;
use DECAROLI\models\Admin;
use DECAROLI\models\Page;
use DECAROLI\models\Image;
use \PDO ; 
use \PDOException;





class DaoAppli
{

    private PDO $db;
    public function __construct()
    {
        $dbObjet  = new Db();
        $this->db = $dbObjet->getDb();  
        // Activez le mode d'erreur PDO
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    private function logError($errorMessage)
    {
        global $filename;
        // Spécifiez le fuseau horaire que vous souhaitez utiliser
        date_default_timezone_set('Europe/Paris'); // Remplacez 'Europe/Paris' par le fuseau horaire de votre choix
        $timestamp = date('d-m-Y H:i:s'); // Obtenez la date et l'heure actuelles au format "Y-m-d H:i:s"
        $fichier   =  __FILE__;
        $logEntry = "$timestamp $fichier - Erreur dans DaoAppli : $errorMessage"  . PHP_EOL;
        error_log($logEntry, 3, $filename, FILE_APPEND);
    }
    public function recuperationUser(?string $identifiant): ?Admin 
    {
        try {
            $requete = Requete::REQ_USER; 
            $stmt = $this->db->prepare($requete);
            $stmt->bindValue(':identifiant', $identifiant, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
                return new Admin($resultat['id_admin'], $resultat['nom'], $resultat['mail'], $resultat['mdp']);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            $this->logError('Erreur PDO dans recuperationUser : ' . $e->getMessage());
            return null;
        }
    }
    
    
    
    public function modifBackgroundTitre($nouveauTitre, $nouveauBackground, $nouvelleCouleurTitre, $nouvelleFontFamily, $nouvelleFontGrand, $nouvelleFontSizeMoyen, $nouvelleFontSizePetit)
    {
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
            // En cas d'erreur PDO, enregistrez l'erreur dans le journal
            $this->logError('Erreur PDO dans modifBackgroundTitre  : ' . $e->getMessage());
        }
    }

    public function getDetailPage()
    {
        $requete = Requete::REQ_DETAIL_PAGE_PROMOTION;
        try {
            $stmt = $this->db->query($requete);
        } catch (PDOException $e) {
            $this->logError('Erreur PDO dans getDetailPage : ' . $e->getMessage());
            return null;
        }

        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$resultat) {
            return null; // ou gérer le cas où il n'y a pas de résultats
        }

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
            $this->logError('Erreur PDO dans getDetailPage : ' . $e->getMessage());
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

    public function traitementImage($nouvelleImage, $nomFichier, $idPage)
    {
        $requete = Requete::REQ_AJOUT_IMAGE;

        try {
            $stmt = $this->db->prepare($requete);
            $stmt->bindParam(':url', $nouvelleImage);
            $stmt->bindParam(':nom_image', $nomFichier);
            $stmt->bindParam(':id_page', $idPage);
            $stmt->execute();

            // Récupérez l'ID de l'image insérée
            $idImage = $this->db->lastInsertId();

            // Créez une nouvelle instance de la classe Image
            $image = new Image($idImage, $nomFichier, $nouvelleImage);

            return $image;
        } catch (PDOException $e) {
            // En cas d'erreur PDO lors de l'exécution de la requête, enregistrez l'erreur dans le journal
            $this->logError('Erreur PDO dans traitementImage  : ' . $e->getMessage());
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
            // En cas d'erreur PDO lors de l'exécution de la requête, enregistrez l'erreur dans le journal
            $this->logError('Erreur PDO dans supprimerImage  :  ' . $e->getMessage());
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

            if ($stmt->execute()) {
                // La modification de l'image a réussi
                return true;
            } else {
                // La modification de l'image a échoué
                return false;
            }
        } catch (PDOException $e) {
            // En cas d'erreur PDO lors de l'exécution de la requête, enregistrez l'erreur dans le journal
            $this->logError('Erreur PDO dans modifierImage  : ' . $e->getMessage());
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
            // En cas d'erreur PDO lors de l'exécution de la requête, enregistrez l'erreur dans le journal
            $this->logError('Erreur PDO dans getNomFichierImageById  : ' . $e->getMessage());
            return null;
        }
    }

  
}
