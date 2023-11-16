<?php

namespace DECAROLI\controllers;

use       DECAROLI\dao\DaoAppli;
use       DECAROLI\controllers\Messages;
use       \PDOException;

ini_set('display_errors', 'Off');
error_reporting(E_ALL);
class CntrlAppli
{
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
    public function afficherPageDashBoard()
    {

        $dao = new DaoAppli();
        $detailPage = $this->getDetailPage();
        $page       = $detailPage['page'];
        $images     = $detailPage['images'];
        // Redirigez l'utilisateur vers la page DashBoard (ou une autre page appropriée) 
        require_once 'src/views/dash_board.php';
    }
    public function afficherPageGestionUser()
    {
        $dao = new DaoAppli();
        $roles = $dao->getRoles();
        $users = $dao->getUserDashboard();
        require_once 'src/views/gestion_user.php';
    }

    public function deconnexion()
    {
        session_start();
        session_destroy();

        // Redirige l'utilisateur vers une page appropriée
        header("Location: /login");
        exit();
    }

    public function connexion()
    {
        //affichage de la page login 
        CntrlAppli::afficherPageLogin();

        // Récupérer les identifiants depuis le formulaire
        $identifiant = htmlspecialchars($_POST['identifiant']);
        $mdp = htmlspecialchars($_POST['mdp']);

        // Vérifier si l'identifiant et le mot de passe ne sont pas vides
        if (empty($identifiant) || empty($mdp)) {
            $errorMessage = Messages::INP_ERR_CHAMP_VIDE;
            Messages::addMessage($errorMessage);
            header('Location: /login');
            exit();
        }

        // Vérification du token de l'API reCAPTCHA 
        if (!empty($_POST['g-recaptcha-response'])) {
            $response = htmlspecialchars($_POST['g-recaptcha-response']);
            $remoteip = $_SERVER['REMOTE_ADDR'];
            include "src/config.php";
            //Appel de l'API reCAPTCHA pour vérifier le token et analyse de la réponse qui contient le score
            $request  = "https://www.google.com/recaptcha/api/siteverify?secret="
                . $config['recaptcha']['CLE_API_SECRET_RECAPTCHA']
                . "&response=$response&remoteip=$remoteip";

            $get  = file_get_contents($request);
            $decode = json_decode($get, true);

            // Si la vérification reCAPTCHA est false ou le score est inférieur 0,7 on rejete la tentative de connexion
            if (!$decode['success'] || $decode['score'] < 0.7) {
                $errorMessage = "Tentative de connexion rejetée en raison d'une activité suspecte détectée.";
                Messages::addMessage($errorMessage);
                header('Location: /login');
                exit();
            }
        } else {
            // G-recaptcha-response est vide, rejet de la tentative de connexion
            $errorMessage = "Le captcha n'a pas été validé.";
            Messages::addMessage($errorMessage);
            header('Location: /login');
            exit();
        }

        // Si le score est suffisant, poursuivre la vérification des identifiants
        try {
            // Vérification des identifiants
            $identifiant = htmlspecialchars($identifiant, ENT_QUOTES, 'UTF-8');
            $mdp = htmlspecialchars($mdp, ENT_QUOTES, 'UTF-8');
            $mdp = hash('sha512', $mdp);

            //objet qui récupère les données de l'utilisateur a partir de l'identifiant
            $dao = new DaoAppli();
            $utilisateurPagePromotion = $dao->recuperationUser($identifiant);
            $utilisateurPagePromotionId = $utilisateurPagePromotion->getIdUtilisateur();
            //récupèration de l'id role de l'utilisateur
            $roleId = $utilisateurPagePromotion->getRole()->getIdRole();

            // Vérifie si le rôle de l'utilisateur est autorisé
            if ($roleId == 1 || $roleId == 2) {
                // Vérifiez si le mot de passe correspond
                if ($utilisateurPagePromotion && $utilisateurPagePromotion->getMdp() === $mdp) {
                    // Mise en place de la session et redirection
                    $_SESSION['utilisateur'] = $utilisateurPagePromotionId;
                    $_SESSION['roleUtilisateur'] = $roleId;
                    header('Location: /dash-board');
                    exit();
                } else {
                    // Identifiants incorrects, ajout d'un message d'erreur
                    $errorMessage = Messages::ERR_LOGIN;
                    Messages::addMessage($errorMessage);
                    header('Location: /login');
                    exit();
                }
            } else {
                // Rôle non autorisé, ajout d'un message d'erreur
                $errorMessage = "Vous n'etes pas autorisé a accéder a cette page";
                Messages::addMessage($errorMessage);
                header("Location: /login");
                exit();
            }
        } catch (PDOException $e) {
            // Gestion de l'erreur PDO
            echo "Erreur PDO : " . $e->getMessage();
            header('Location: /login');
            exit();
        }
    }

    public function getDetailPage()
    {
        $dao = new DaoAppli();
        $detailPage = $dao->getDetailPageEtImages();
        return $detailPage;
    }

    //Méthode qui traite les données du formulaire et les images téléchargées
    public function traitementFormulaire()
    {
        // Nettoyage des champs du formulaire avec htmlspecialchars 
        $nouveauTitre          = htmlspecialchars($_POST['titre'], ENT_QUOTES, 'UTF-8');
        $nouveauBackground     = htmlspecialchars($_POST['background'], ENT_QUOTES, 'UTF-8');
        $nouvelleCouleurTitre  = htmlspecialchars($_POST['titre_color'], ENT_QUOTES, 'UTF-8');
        $nouvelleFontFamily    = htmlspecialchars($_POST['titre_font_family'], ENT_QUOTES, 'UTF-8');
        $nouvelleFontSizeGrand = htmlspecialchars($_POST['titre_font_size_grand_ecran'], ENT_QUOTES, 'UTF-8');
        $nouvelleFontSizeMoyen = htmlspecialchars($_POST['titre_font_size_moyen_ecran'], ENT_QUOTES, 'UTF-8');
        $nouvelleFontSizePetit = htmlspecialchars($_POST['titre_font_size_petit_ecran'], ENT_QUOTES, 'UTF-8');

        // Création de l'objet afin d'intéragir avec la base de données
        $dao = new DaoAppli();
        //Appel de la methode de l'objet dao afin de mettre a jour les données dans la base de données
        $dao->modifBackgroundTitre(
            $nouveauTitre,
            $nouveauBackground,
            $nouvelleCouleurTitre,
            $nouvelleFontFamily,
            $nouvelleFontSizeGrand,
            $nouvelleFontSizeMoyen,
            $nouvelleFontSizePetit
        );

        //Vérifivation de l'image téléchargé
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            //Stockage le type de l'image téléchargé
            $typeFichier = $_FILES['image']['type'];
            //Vérification du format de l'image 
            $extensionsAutorisées = array('image/jpeg', 'image/gif', 'image/png');
            //
            if (!in_array($typeFichier, $extensionsAutorisées)) {
                Messages::addMessage("Seules les images au format JPEG, PNG ou GIF sont autorisées.");
            } else {
                //Le format correspond a l'extension autorisé, on appel la methode en passant
                //la variable superglobal en parametre pour acceder aux données du fichier   
                $this->traiterImageTelecharge($_FILES['image']);
            }
        }

        header('Location: /dash-board');
    }

    //Méthode qui gère le traitement des images
    private function traiterImageTelecharge($image)
    {
        //Récupèration du nom original du fichier et le chemin temporaire où l'image est stockée
        $nomFichier = $image['name'];
        $fichierTemporaire = $image['tmp_name'];
        //Création d'un nom de fichier unique pour éviter les conflits de noms dans le repértoire
        $nomUnique = uniqid() . '_' . $nomFichier;
        //le chemin où l'image sera stockée
        $cheminStockage = 'assets/ressources/images/' . $nomUnique;
        //Si le redimentionnement est réussi l'image est enregistré et traité
        if ($this->redimensionnerImage($fichierTemporaire, $cheminStockage, 600, 600)) {
            //Création d'une instance de DaoAppli pour interagir avec la base de données
            $dao = new DaoAppli();
            //Appelle de la méthode de traitement de l'image dans DaoAppli 
            $dao->traitementImage($nomUnique, $nomFichier, 1); // 1 = l'ID de la page
        } else {
            Messages::addMessage("Erreur lors du traitement de l'image.");
        }
    }

    //Méthode qui redimentionne l'image, en paramètre : chemin temporaire ou l'image est stockée sur le serveur
    //chemin de destination ou l'image redimensionnée sera sauvegardé, largeur max, hauteur max souhaitée                 
    public function redimensionnerImage($cheminSource, $cheminCible, $largeurMax, $hauteurMax)
    {
        // Vérifie si le fichier image source existe
        if (!file_exists($cheminSource)) {
            Messages::addMessage("Le fichier source n'existe pas : " . $cheminSource);
            return false;
        }
        // Obtention des dimensions et le type de l'image source avec getimagesize(largeur,hauteur,type)
        list($largeurOrig, $hauteurOrig, $typeOrig) = getimagesize($cheminSource);

        // Création d'un objet image à partir du fichier source en fonction du type 
        $imageSource = $this->creerImageSource($cheminSource, $typeOrig);
        if (!$imageSource) {
            Messages::addMessage("Type d'image non pris en charge : " . $typeOrig);
            return false;
        }
        // Calcule le ratio pour redimensionner l'image tout en conservant les proportions
        $ratio = min($largeurMax / $largeurOrig, $hauteurMax / $hauteurOrig);
        //nouvelle dimension arrondi 
        $nouvelleLargeur = round($largeurOrig * $ratio);
        $nouvelleHauteur = round($hauteurOrig * $ratio);
        // Création d'une nouvelle image vide de couleur noire avec les nouvelles dimensions 
        $nouvelleImage = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);
        // // Paramétrage pour conserver la transparence pour les images PNG et GIF
        imagealphablending($nouvelleImage, false);
        imagesavealpha($nouvelleImage, true);
        // Copie et redimensionne l'ancienne image dans la nouvelle 
        imagecopyresampled(
            $nouvelleImage,
            $imageSource,
            0,
            0,
            0,
            0,
            $nouvelleLargeur,
            $nouvelleHauteur,
            $largeurOrig,
            $hauteurOrig
        );

        // Sauvegarde la nouvelle image dans le chemin cible, selon son format
        switch ($typeOrig) {
            case IMAGETYPE_JPEG:
                imagejpeg($nouvelleImage, $cheminCible);
                break;
            case IMAGETYPE_PNG:
                imagepng($nouvelleImage, $cheminCible);
                break;
            case IMAGETYPE_GIF:
                imagegif($nouvelleImage, $cheminCible);
                break;
        }
        // Libère la mémoire associée aux images dans le serveur
        imagedestroy($nouvelleImage);
        imagedestroy($imageSource);
        // Retourne true pour indiquer que le redimensionnement a réussi
        return true;
    }


    private function creerImageSource($chemin, $type)
    {
        //Traitement des différents types d'images
        switch ($type) {
                // Pour les images JPEG, utilise la fonction imagecreatefromjpeg
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($chemin);
                // Pour les images PNG, utilise la fonction imagecreatefrompng
            case IMAGETYPE_PNG:
                return imagecreatefrompng($chemin);
                // Pour les images GIF, utilise la fonction imagecreatefromgif
            case IMAGETYPE_GIF:
                return imagecreatefromgif($chemin);
                // Si le type de l'image n'est pas pris en charge, retourne false
            default:
                return false;
        }
    }

    public function supprimerImage()
    {   //Récuperation de l'id de l'image avec GET 
        $idImageASupprimer = isset($_GET['idImage']) ? intval($_GET['idImage']) : 0;
        //Vérification de l'ID de l'image 
        if ($idImageASupprimer > 0) {
            //Objet de la class DaoAppli est instancié 
            $dao = new DaoAppli();
            // Récupére le nom du fichier image à partir de la base de données
            $nomFichierImage = $dao->getNomFichierImageById($idImageASupprimer);

            // Supprime l'image de la base de données
            $dao->supprimerImage($idImageASupprimer);

            // Supprime le fichier image du répertoire de stockage
            if (!empty($nomFichierImage)) {
                $cheminFichierImage = 'assets/ressources/images/' . $nomFichierImage;
                if (file_exists($cheminFichierImage)) {
                    unlink($cheminFichierImage);
                }
            }
        }
        //Réponse a la requete Ajax traité avec succés
        http_response_code(200);
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
                    header('Location: /dash-board');
                    exit;
                }

                $extensionsAutorisees = array('jpg', 'jpeg', 'png');
                $nomFichier = $_FILES['nouvelleImage']['name'];
                $extensionFichier = pathinfo($nomFichier, PATHINFO_EXTENSION);

                if (!in_array(strtolower($extensionFichier), $extensionsAutorisees)) {
                    $_SESSION['messageImageError'] = "Seules les images au format JPEG, PNG ou GIF sont autorisées.";
                    header('Location: /dash-board');
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
                        // Redirigez l'utilisateur vers la page dash-board pour recharger la page
                        header('Location: /dash-board');
                        exit();
                    } else {
                        // La modification de l'image a échoué
                        // Gérez l'échec (par exemple, affichez un message d'erreur)
                        // Vous pouvez envoyer une réponse JSON si nécessaire
                        $_SESSION['messageImageError'] = "La modification de l'image a échoué.";
                        header('Location: /dash-board?error=1'); // Vous pouvez utiliser un paramètre "error" pour indiquer l'erreur
                    }
                } else {
                    $_SESSION['messageImageError'] = "Erreur lors du déplacement du fichier.";
                }
            }
            header('Location: /dash-board');
            exit();
        }
        // Redirigez l'utilisateur vers la page d'origine ou une autre page
        header('Location: /dash-board');
        exit();
        // Affichez la valeur de $idImageModifier à des fins de débogage
    }

    public function traitementAjoutUser()
    {
        $nom      = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
        $mail     = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');
        $mdp      = htmlspecialchars($_POST['mdp'], ENT_QUOTES, 'UTF-8');
        $mdpHasher      = hash('sha512', $mdp);
        $role     = htmlspecialchars($_POST['role'], ENT_QUOTES, 'UTF-8');
        $dao = new DaoAppli();
        $ajoutUtilisateur = $dao->ajoutUtilisateur($nom, $mail, $mdpHasher, $role);

        header('Location: /gestion-user');
    }

    public function supprimerUser()
    { //Récuperation de l'id de l'utilisateur avec GET 
        $idUtilisateurASupprimer = isset($_GET['idUser']) ? intval($_GET['idUser']) : 0;
        //Vérification de l'ID de l'utilisateur 
        if ($idUtilisateurASupprimer > 0) {
            //Objet de la class DaoAppli est instancié 
            $dao = new DaoAppli();
            // Supprime l'image de la base de données
            $dao->supprimerUser($idUtilisateurASupprimer);
        }
        //Réponse a la requete Ajax traité avec succés
        http_response_code(200);
        exit();
    }

    public function modifierUser()
    {
        $idUtilisateur = isset($_POST['idUtilisateur']) ? intval($_POST['idUtilisateur']) : 0;
        if ($idUtilisateur > 0) {
            $nom      = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
            $mail     = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');
            $mdp      = htmlspecialchars($_POST['mdp'], ENT_QUOTES, 'UTF-8');
            $mdpHasher      = hash('sha512', $mdp);
            $role     = htmlspecialchars($_POST['role'], ENT_QUOTES, 'UTF-8');

            $dao = new DaoAppli();
            $modifierUser = $dao->modifierUserDao($nom, $mdpHasher, $mail, $role, $idUtilisateur);
            header('Location: /gestion-user');
        }
        header('Location: /gestion-user');
    }
}
