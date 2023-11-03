<?php
namespace DECAROLI\controllers;
use       DECAROLI\dao\DaoAppli;
use       DECAROLI\controllers\Messages;
use       DECAROLI\models;
use       \PDO;
use       \PDOException;

require_once 'src/controllers/Message.php';
require_once 'src/dao/DaoAppli.php';
require_once 'src/controllers/Message.php';
require_once 'src/dao/Requete.php';
require_once 'src/models/Page.php';
require_once 'src/models/Image.php';
require_once 'src/dao/Requete.php';



class CntrlAppli
{

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
    public function afficherPagePromo()
    {
        $detailPage = $this->getDetailPage();
        $page =   $detailPage['page'];
        $images = $detailPage['images'];

        require_once 'src/views/index.php';
    }

    public function afficherPageLogin()
    {
        require_once 'src/views/login.php';
    }
    public function afficherPageAdmin()
    {

        // $dao = new DaoAppli();
        $detailPage = $this->getDetailPage();
        $page       = $detailPage['page'];
        $images     = $detailPage['images'];
        // Redirigez l'utilisateur vers la page admin (ou une autre page appropriée) 
        require_once 'src/views/admin.php';
    }

    public function connexion()
    {
        require_once 'src/dao/DaoAppli.php';

        // Réinitialisez les messages d'erreur à chaque nouvelle tentative de connexion
        Messages::setErrorMessage([]);
 
        // Tableau pour stocker les messages d'erreur
        $errorMessage = [];
        $_SESSION['errorMessage'] = $errorMessage;
        
        // Message::setErrorMessage($errorMessage); 
        $identifiant = $_POST['identifiant'] ?? null;
        $mdp = $_POST['mdp'] ?? null;


        try {
            $identifiant = htmlspecialchars($identifiant, ENT_QUOTES, 'UTF-8');
            $mdp = htmlspecialchars($mdp, ENT_QUOTES, 'UTF-8');
            $mdp = hash('sha512', $mdp);

            // recupération du nom et mail de l'utilisateur
            $dao = new DaoAppli();
            $adminPagePromotion = $dao->recuperationUser($identifiant);

            if (!empty($_POST['nom']) && !empty($_POST['mdp'])) {
                        // $secret = "6Lfkds8oAAAAACgUwWz39ekeIDucyKmPvKfuerP5";
                        $response = htmlspecialchars($_POST['g-recaptcha-response']);
                        $remoteip = $_SERVER['REMOTE_ADDR'];
                        $request  = "https://www.google.com/recaptcha/api/siteverify?secret=" . $config['recaptcha']['CLE_API_SECRET_RECAPTCHA'] . "&response=$response&remoteip=$remoteip";
                        
                        $get  = file_get_contents($request);
                        $decode = json_decode($get, true);
                        var_dump($decode);
                        // if ($decode['success'] && $decode['score'] == 0.9) {
                        if (true) { 
                            // Le score est supérieur ou égal à 0.7, la validation est réussie
                            // Redirigez l'utilisateur vers la page admin
                            $this->logError('Erreur SCORE  : ' . $decode);
                            header('Location: /admin');
                            // exit;
                        }
                        //  else {
                        //     // Le score est inférieur à 0.7, la validation est rejetée
                        //     $errorMessage[] = "Votre tentative de connexion a été rejetée en raison d'une activité suspecte détectée";
                        //     var_dump($decode);
                        //     $_SESSION['errorMessage'] = $errorMessage;  // Stockez les messages d'erreur dans la session
                        //     header('Location: /login');
                        // }
                        // 

                        require_once 'src/views/login.php';
                    }

            // if ($adminPagePromotion && $adminPagePromotion->getMdp() === $mdp) {
            //     $_SESSION['nom'] = $adminPagePromotion->getNom();
            //     $_SESSION['mail'] = $adminPagePromotion->getMail();
            //     header('Location: /admin');
            //     exit;
            // }  else {
            //      $errorMessage[] = Messages::ERR_LOGIN;
            //     $this->logError('Échec de la connexion utilisateur : identifiants incorrects');
            // }
            
        } catch (PDOException $e) {
            $this->logError('Erreur PDO dans connexionUser  : ' . $e->getMessage());
        }

        if (empty($identifiant) || empty($mdp)) {
            $errorMessage[] = Messages::INP_ERR_CHAMP_VIDE;
            $_SESSION['errorMessage'] = $errorMessage;
            header('Location: /login');
        }

       
        

        $_SESSION['errorMessage'] = $errorMessage;
   
        // Restez dans la même vue pour afficher les messages d'erreur
        require_once 'src/views/login.php';
    }

    public function deconnexion()
    {
        session_start();
        session_destroy();

        // Redirige l'utilisateur vers une page appropriée
        header("Location: /login");
        exit();
    }

    public function getDetailPage()
    {
        $dao = new DaoAppli();
        $detailPage = $dao->getDetailPageEtImages();
        return $detailPage;
    }

    public  function redimensionnerImage($cheminSource, $cheminCible, $largeurMax, $hauteurMax)
    {
        if (file_exists($cheminSource)) {
            list($largeurOrig, $hauteurOrig, $typeOrig) = getimagesize($cheminSource);

            switch ($typeOrig) {
                case IMAGETYPE_JPEG:
                    $imageSource = imagecreatefromjpeg($cheminSource);
                    break;
                case IMAGETYPE_PNG:
                    $imageSource = imagecreatefrompng($cheminSource);
                    break;
                case IMAGETYPE_GIF:
                    $imageSource = imagecreatefromgif($cheminSource);
                    break;
                default:
                    echo "Type d'image non pris en charge : " . $typeOrig;
                    return;
            }

            $ratio = min($largeurMax / $largeurOrig, $hauteurMax / $hauteurOrig);
            $nouvelleLargeur = round($largeurOrig * $ratio);
            $nouvelleHauteur = round($hauteurOrig * $ratio);

            $nouvelleImage = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);

            if ($typeOrig === IMAGETYPE_PNG) {
                // Créez une image avec fond transparent
                $nouvelleImage = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);
                imagealphablending($nouvelleImage, false);
                imagesavealpha($nouvelleImage, true);
                $transparent = imagecolorallocatealpha($nouvelleImage, 255, 255, 255, 127);
                imagefilledrectangle($nouvelleImage, 0, 0, $nouvelleLargeur, $nouvelleHauteur, $transparent);
            } else {
                // Créez une image avec fond blanc pour les autres formats
                $nouvelleImage = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);
                $blanc = imagecolorallocate($nouvelleImage, 255, 255, 255);
                imagefill($nouvelleImage, 0, 0, $blanc);
            }

            imagecopyresampled($nouvelleImage, $imageSource, 0, 0, 0, 0, $nouvelleLargeur, $nouvelleHauteur, $largeurOrig, $hauteurOrig);

            if ($typeOrig === IMAGETYPE_JPEG) {
                imagejpeg($nouvelleImage, $cheminCible, 100);
            } elseif ($typeOrig === IMAGETYPE_PNG) {
                imagepng($nouvelleImage, $cheminCible);
            } elseif ($typeOrig === IMAGETYPE_GIF) {
                imagegif($nouvelleImage, $cheminCible);
            }

            imagejpeg($nouvelleImage, $cheminCible, 100);

            switch ($typeOrig) {
                case IMAGETYPE_JPEG:
                    imagejpeg($nouvelleImage, $cheminCible, 100);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($nouvelleImage, $cheminCible);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($nouvelleImage, $cheminCible);
                    break;
            }

            imagedestroy($nouvelleImage);
            imagedestroy($imageSource);
        } else {
            echo "Le fichier source n'existe pas : " . $cheminSource;
        }
    }

    public function traitementFormulaire()
    {
        
        $nouveauTitre          = htmlspecialchars($_POST['titre'], ENT_QUOTES, 'UTF-8');
        $nouveauBackground     = htmlspecialchars($_POST['background'], ENT_QUOTES, 'UTF-8');
        $nouvelleCouleurTitre  = htmlspecialchars($_POST['titre_color'], ENT_QUOTES, 'UTF-8');
        $nouvelleFontFamily    = htmlspecialchars($_POST['titre_font_family']);
        $nouvelleFontSizeGrand = htmlspecialchars($_POST['titre_font_size_grand_ecran'], ENT_QUOTES, 'UTF-8');
        $nouvelleFontSizeMoyen = htmlspecialchars($_POST['titre_font_size_moyen_ecran'], ENT_QUOTES, 'UTF-8');
        $nouvelleFontSizePetit = htmlspecialchars($_POST['titre_font_size_petit_ecran'], ENT_QUOTES, 'UTF-8');

        // Mettez à jour le fond d'écran de la page dans DaoAppli
        $dao = new DaoAppli();  
        $dao->modifBackgroundTitre($nouveauTitre, $nouveauBackground, $nouvelleCouleurTitre, $nouvelleFontFamily, $nouvelleFontSizeGrand, $nouvelleFontSizeMoyen, $nouvelleFontSizePetit);
        $pageMiseAJour = $dao->getDetailPage();
        if ($pageMiseAJour === null) {
            // Gérez l'erreur ici (par exemple, enregistrez un message d'erreur dans un journal, affichez un message à l'utilisateur, etc.)
            echo "Une erreur s'est produite lors de la mise à jour de la page.";
        } 
        // $donneesOrigine = $this->getDetailPage();

        

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $nomFichier = $_FILES['image']['name'];
            $typeFichier = $_FILES['image']['type'];
            $fichierTemporaire = $_FILES['image']['tmp_name'];

            $extensionsAutorisées = array('image/jpeg', 'image/gif', 'image/png');

            if (!in_array($typeFichier, $extensionsAutorisées)) {
                $_SESSION['messageImageError'] = "Seules les images au format JPEG, PNG ou GIF sont autorisées.";
                header('Location: /admin');
                exit();
            }

            $nomUnique = uniqid() . '_' . $nomFichier;
            $cheminStockage = 'assets/ressources/images/' . $nomUnique;

            // Ici, vous devriez utiliser $fichierTemporaire pour créer l'image plutôt que $cheminStockage
            $largeurMax = 800;
            $hauteurMax = 600;


            if ($typeFichier === 'image/jpeg') {
                $imageSource = imagecreatefromjpeg($fichierTemporaire);
                header('Location: /admin');
            } elseif ($typeFichier === 'image/png') {
                $imageSource = imagecreatefrompng($fichierTemporaire);
                header('Location: /admin');
            } elseif ($typeFichier === 'image/gif') {
                $imageSource = imagecreatefromgif($fichierTemporaire);
                header('Location: /admin');
            } else {
                $_SESSION['messageImageError'] = "Type d'image non pris en charge.";
                header('Location: /admin');
                exit();
            }
            var_dump($typeFichier);
            $this->redimensionnerImage($fichierTemporaire, $cheminStockage, $largeurMax, $hauteurMax);

            $idPage = 1; // Remplacez par l'ID de la page appropriée
            $dao->traitementImage($nomUnique, $nomFichier, $idPage);
            echo $nomFichier;
        }
        header('Location: /admin');
    }

    public function supprimerImage()
    {
        $idImageASupprimer = isset($_GET['idImage']) ? intval($_GET['idImage']) : 0;

        if ($idImageASupprimer > 0) {
            $dao = new DaoAppli();

            // Récupérez le nom du fichier image à partir de la base de données
            $nomFichierImage = $dao->getNomFichierImageById($idImageASupprimer);

            // Supprimez l'image de la base de données
            $dao->supprimerImage($idImageASupprimer);

            // Supprimez le fichier image du répertoire de stockage
            if (!empty($nomFichierImage)) {
                $cheminFichierImage = 'assets/ressources/images/' . $nomFichierImage;
                if (file_exists($cheminFichierImage)) {
                    unlink($cheminFichierImage);
                }
            }
        }

        // Répondez à la requête AJAX
        http_response_code(200); // OK

        exit();
    }


    public function modifierImage()
    {
        require_once 'src/dao/DaoAppli.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérez les données du formulaire...
            $idImageModifier = isset($_POST['idImageModifier']) ? intval($_POST['idImageModifier']) : 0;
            $nouveauNomImage = isset($_POST['nouveau_nom_image']) ? $_POST['nouveau_nom_image'] : '';

            // Affichez la valeur de $idImageModifier à des fins de débogage
            $_SESSION['idImageModifier'] = $idImageModifier;
            $_SESSION['nouveauNomImage'] = $nouveauNomImage;

            // Assurez-vous que l'ID de l'image est valide
            if ($idImageModifier > 0) {
                $dao = new DaoAppli();

                // Informations sur le fichier téléchargé
                if (!isset($_FILES['nouvelleImage']['error']) || $_FILES['nouvelleImage']['error'] !== UPLOAD_ERR_OK) {
                    $_SESSION['messageImageError'] = "Erreur lors du téléchargement de la nouvelle image.";
                    header('Location: /admin');
                    exit;
                }

                $extensionsAutorisees = array('jpg', 'jpeg', 'png');
                $nomFichier = $_FILES['nouvelleImage']['name'];
                $extensionFichier = pathinfo($nomFichier, PATHINFO_EXTENSION);

                if (!in_array(strtolower($extensionFichier), $extensionsAutorisees)) {
                    $_SESSION['messageImageError'] = "Seules les images au format JPEG, PNG ou GIF sont autorisées.";
                    header('Location: /admin');
                    exit;
                }

                $fichierTemporaire = $_FILES['nouvelleImage']['tmp_name'];

                // Générez un nom unique pour le fichier pour éviter l'écrasement d'image
                $nomUnique = uniqid() . '_' . $nomFichier;
                $cheminStockage = 'assets/ressources/images/' . $nomUnique;
                $_SESSION['nomUnique'] = $nomUnique;
                // Déplacez le fichier téléchargé vers le répertoire de stockage des images
                if (move_uploaded_file($fichierTemporaire, $cheminStockage)) {
                    // Le téléchargement a réussi, vous pouvez maintenant insérer le nom du fichier dans la base de données
                    $resultat = $dao->getNomFichierImageById($idImageModifier); // Récupérez le nom actuel du fichier
                    $ancienNomImage = $resultat; // Nom du fichier actuel dans la base de données

                    // Affichez les valeurs de $nomUnique et $ancienNomImage à des fins de débogage

                    $_SESSION['ancienNom'] = $ancienNomImage;

                    // Mettez à jour le nom de l'image dans la base de données
                    $resultat = $dao->modifierImage($nouveauNomImage, $nomUnique, $idImageModifier);

                    if ($resultat) {
                        // La modification de l'image a réussi

                        // Supprimez l'ancien fichier image s'il existe
                        if (!empty($ancienNomImage)) {
                            $cheminAncienFichier = 'assets/ressources/images/' . $ancienNomImage;
                            if (file_exists($cheminAncienFichier)) {
                                unlink($cheminAncienFichier); // Supprimez le fichier
                            }
                        }

                        // Redimensionnez l'image après le téléchargement
                        $cheminImageRedimensionnee = 'assets/ressources/images/' . $nomUnique; // Remplacez par le chemin de votre répertoire
                        $largeurMax = 800; // Remplacez par la largeur maximale souhaitée
                        $hauteurMax = 600; // Remplacez par la hauteur maximale souhaitée
                        $this->redimensionnerImage($cheminStockage, $cheminImageRedimensionnee, $largeurMax, $hauteurMax);
                        // Redirigez l'utilisateur vers la page admin pour recharger la page
                        header('Location: /admin');
                        exit();
                    } else {
                        // La modification de l'image a échoué
                        // Gérez l'échec (par exemple, affichez un message d'erreur)
                        // Vous pouvez envoyer une réponse JSON si nécessaire
                        $_SESSION['messageImageError'] = "La modification de l'image a échoué.";
                        header('Location: /admin?error=1'); // Vous pouvez utiliser un paramètre "error" pour indiquer l'erreur
                    }
                } else {
                    $_SESSION['messageImageError'] = "Erreur lors du déplacement du fichier.";
                }
            }
            header('Location: /admin');
            exit();
        }
        // Redirigez l'utilisateur vers la page d'origine ou une autre page
        header('Location: /admin');
        exit();
        // Affichez la valeur de $idImageModifier à des fins de débogage
    }
}