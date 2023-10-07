<?php
require_once 'src/controllers/Message.php';
require_once 'src/dao/DaoAppli.php';
require_once 'src/controllers/Message.php';
require_once 'src/dao/Requete.php';
class CntrlAppli {

            public function afficherPagePromo()
        {
            $dao = new DaoAppli();
            // $infoImages = $dao->getImages();
            $donneesOrigine = $this->getDonneestable();
            require_once 'src/views/index.php';
        }

        public function afficherPageLogin()
        {
            require_once 'src/views/login.php';
        }
        public function afficherPageAdmin()
        {

              $dao = new DaoAppli();
            $donneesOrigine = $this->getDonneestable();
            // Redirigez l'utilisateur vers la page admin (ou une autre page appropriée)
            require_once 'src/views/admin.php';
           
        }

        public function connexion()
        {
            require_once 'src/dao/DaoAppli.php';

            
            
            $nom = isset($_POST['nom']) ? trim(addcslashes(strip_tags($nom), '\x00..\x1F')) : '';
            $mdp = isset($_POST['mdp']) ? trim(addcslashes(strip_tags($mdp), '\x00..\x1F')) : '';

            // Réinitialisez les messages d'erreur à chaque nouvelle tentative de connexion
            Message::setErrorMessage([]);

            // Tableau pour stocker les messages d'erreur
            $errorMessage = [];

            if(empty($nom) || empty($mdp)){
                $errorMessageVide = Message::INP_ERR_CHAMP_VIDE;
            }
            
            // Vérifiez la longueur minimale du nom d'utilisateur
            if (empty($nom) || strlen($nom) < 4) {
                // $errorMessage[] = Message::INP_ERR_NOM_CHAR;
            }
            
            // Vérifiez la complexité du mot de passe
            if (empty($mdp) || strlen($mdp) < 8) {
                // $errorMessage[] = Message::INP_ERR_MDP_CHAR;
            } else {
                // Exigez au moins une lettre majuscule, une lettre minuscule et un chiffre
                // if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/', $mdp)) {
                //     $errorMessage[] = Message::INP_ERR_MDP_CHAR_SPE;
                // }
            }

            
            $dao = new DaoAppli();
            $errorMessageFromDao = $dao->connexionUser($nom, $mdp); 

            // Ajoutez les messages d'erreur du DaoAppli aux messages d'erreur existants
            $errorMessage = array_merge($errorMessage, $errorMessageFromDao);
            Message::setErrorMessage($errorMessage);
            
          if (!empty($errorMessage)) {
    require_once 'src/views/login.php';
} else { 
    // Redirigez l'utilisateur après une connexion réussie
    header('Location: /admin');
    exit; // Assurez-vous d'appeler exit() pour arrêter l'exécution du script après la redirection
}
        }

        public function deconnexion(){
            session_start();
            session_destroy(); 

            // Redirige l'utilisateur vers une page appropriée
            header("Location: /login");
            exit();
        }


        public function getDonneestable() {
            $dao = new DaoAppli();
            $donneesOrigine = $dao->getTitreCouleurImage();
            return $donneesOrigine;
        }

        public function traitementFormulaire(){
            $nouveauTitre =         htmlspecialchars($_POST['titre'], ENT_QUOTES, 'UTF-8');
            $nouveauBackground =    htmlspecialchars($_POST['background'], ENT_QUOTES, 'UTF-8');
            $nouvelleCouleurTitre = htmlspecialchars($_POST['titre_color'], ENT_QUOTES, 'UTF-8');
            
            // Mettez à jour le fond d'écran de la page dans DaoAppli
            $dao = new DaoAppli();
            $dao->modifBackgroundTitre($nouveauTitre, $nouveauBackground, $nouvelleCouleurTitre); 
            
            $donneesOrigine = $this->getDonneestable();
            // Initialisez le tableau des informations sur les images
            
            if (isset($_FILES['image'])) {
                // Informations sur le fichier téléchargé
                $nomFichier = $_FILES['image']['name']; // Nom du fichier image
                $typeFichier = $_FILES['image']['type'];
                $tailleFichier = $_FILES['image']['size'];
                $fichierTemporaire = $_FILES['image']['tmp_name'];
                
                // Vérifier que le fichier est une image ex: le type
                $extensionsAutorisées = array('image/jpeg', 'image/png', 'image/gif');
                if (!in_array($typeFichier, $extensionsAutorisées)) {
                    $_SESSION['messageImageError'] = "Seules les images au format JPEG, PNG ou GIF sont autorisées.";
                    header('Location: /admin');
                    exit;
                }
                
                // Générez un nom unique pour le fichier pour éviter l'écrasement d'image
                $nomUnique = uniqid() . '_' . $nomFichier;  
                $cheminStockage = 'assets/ressources/images/' . $nomUnique;
                
                // Déplacez le fichier téléchargé vers le répertoire de stockage des images
                if (move_uploaded_file($fichierTemporaire, $cheminStockage)) {
                    // Le téléchargement a réussi, vous pouvez maintenant insérer le nom du fichier dans la base de données
                    $idPage = 1; // Remplacez par l'ID de la page appropriée
                    $dao->traitementImage($nomUnique, $nomFichier, $idPage); 
                    echo $nomFichier;
                }
            }
            header('Location: /admin');
            exit; // Assurez-vous d'appeler exit() pour arrêter l'exécution du script après la redirection
        }
        
        public function supprimerImage() {
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
        

        public function modifierImage() {
            require_once 'src/dao/DaoAppli.php';
        
            // Vérifiez si la requête est de type POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Récupérez l'identifiant de l'image depuis la requête ou le formulaire
                $idImageModifier = isset($_POST['idImageModifier']) ? intval($_POST['idImageModifier']) : 0;
        
                // Affichez la valeur de $idImageModifier à des fins de débogage
                echo "Valeur de \$idImageModifier : " . $idImageModifier;
        
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
        
                    // Déplacez le fichier téléchargé vers le répertoire de stockage des images
                    if (move_uploaded_file($fichierTemporaire, $cheminStockage)) {
                        // Le téléchargement a réussi, vous pouvez maintenant insérer le nom du fichier dans la base de données
                        $resultat = $dao->getNomFichierImageById($idImageModifier); // Récupérez le nom actuel du fichier
                        $ancienNomImage = $resultat['nom_image']; // Nom du fichier actuel dans la base de données
        
                        // Affichez les valeurs de $nomUnique et $ancienNomImage à des fins de débogage
                        echo "Nom unique : " . $nomUnique;
                        echo "Ancien nom d'image : " . $ancienNomImage;
        
                        // Mettez à jour le nom de l'image dans la base de données
                        $resultat = $dao->modifierImage($nomUnique, $cheminStockage, $idImageModifier);
        
                        if ($resultat) {
                            // La modification de l'image a réussi
        
                            // Supprimez l'ancien fichier image s'il existe
                            if (!empty($ancienNomImage)) {
                                $cheminAncienFichier = 'assets/ressources/images/' . $ancienNomImage;
                                if (file_exists($cheminAncienFichier)) {
                                    unlink($cheminAncienFichier); // Supprimez le fichier
                                }
                            }
        
                            // Vous pouvez envoyer une réponse JSON si nécessaire
                        } else {
                            // La modification de l'image a échoué
                            // Gérez l'échec (par exemple, affichez un message d'erreur)
                            // Vous pouvez envoyer une réponse JSON si nécessaire
                            $_SESSION['messageImageError'] = "La modification de l'image a échoué.";
                        }
                    } else {
                        $_SESSION['messageImageError'] = "Erreur lors du déplacement du fichier.";
                    }
                }
                header('Location: /admin');
                exit;
            }
            // Redirigez l'utilisateur vers la page d'origine ou une autre page
            header('Location: /admin');
            exit;
        }
}