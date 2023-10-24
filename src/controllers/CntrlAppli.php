<?php
require_once 'src/controllers/Message.php';
require_once 'src/dao/DaoAppli.php';
require_once 'src/controllers/Message.php';
require_once 'src/dao/Requete.php';

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require_once '../../vendor/autoload.php';

// $mail = new PHPMailer(true);

// try {
//     $mail->isSMTP();
//     $mail->Host = 'smtp.live.com';
//     $mail->SMTPAuth = true;
//     $mail->Username = ''; // Votre adresse email
//     $mail->Password = ''; // Votre mot de passe email
//     $mail->SMTPSecure = 'tls';
//     $mail->Port = 587;

//     $mail->setFrom('', 'Sofiane Tortosa');
//     $mail->addAddress('', 'Sofiane Tortosa');
//     $mail->isHTML(true);
//     $mail->Subject = "test email";
//     $mail->Body = "Ceci est un e-mail de test envoyé à moi-même pour vérifier le fonctionnement de PHPMailer.";

//     $mail->SMTPDebug = 2;  // 2 pour afficher les messages d'erreur, 0 pour les désactiver

//     if (!$mail->send()) {
//         echo "L'email n'a pas été envoyé. Mailer Error: {$mail->ErrorInfo}";
//     } else {
//         echo "Email envoyé avec succès!";
//     }
// } catch (Exception $e) {
//     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
// }
class CntrlAppli {
 
            public function afficherPagePromo()
        {
            $dao = new DaoAppli();
            
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
          // Réinitialisez les messages d'erreur à chaque nouvelle tentative de connexion
                    Message::setErrorMessage([]);
        
                    // Tableau pour stocker les messages d'erreur
                    $errorMessage = [];
        
                    if (empty($nom) || empty($mdp)) {
                        $errorMessageVide = Message::INP_ERR_CHAMP_VIDE;
                    }
                    if (empty($nom) && empty($mdp)) {
                        $errorMessageVide = Message::INP_ERR_CHAMP_VIDE;
                    }
        
                    // Vérifiez la longueur minimale du nom d'utilisateur
                    // if (empty($nom) || strlen($nom) < 4) {
                    //     $errorMessage[] = Message::INP_ERR_NOM_CHAR;
                    // }
        
                    // // Vérifiez la complexité du mot de passe
                    // if (empty($mdp) || strlen($mdp) < 8) {
                    //     $errorMessage[] = Message::INP_ERR_MDP_CHAR;
                    // } else {
                    //     // Exigez au moins une lettre majuscule, une lettre minuscule et un chiffre
                    //     if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/', $mdp)) {
                    //         $errorMessage[] = Message::INP_ERR_MDP_CHAR_SPE;
                    //     }
                    // }
                    $dao = new DaoAppli();
                    $errorMessageFromDao = $dao->connexionUser($nom, $mdp);
        
                    // Ajoutez les messages d'erreur du DaoAppli aux messages d'erreur existants
                    $errorMessage = array_merge($errorMessage, $errorMessageFromDao);
        
                    Message::setErrorMessage($errorMessage);


            if (!empty($_POST['nom']) && !empty($_POST['mdp'])) {
                $secret = "6LfFS8IoAAAAAGv-NyUbPuCwN07K4M-qKX0phMyL";
                $response = htmlspecialchars($_POST['g-recaptcha-response']);
                $remoteip = $_SERVER['REMOTE_ADDR'];
                $request  = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
        
                $get  = file_get_contents($request);
                $decode = json_decode($get, true);
        
                var_dump($decode);
                // exit;
            if ($decode['success'] && $decode['score'] >= 0.7) {
                    // Le score est supérieur ou égal à 0.7, la validation est réussie
                    // Ajoutez le reste de votre code de traitement de connexion ici
                    // $nom = isset($_POST['nom']) ? trim(addcslashes(strip_tags($nom), '\x00..\x1F')) : '';
                    // $mdp = isset($_POST['mdp']) ? trim(addcslashes(strip_tags($mdp), '\x00..\x1F')) : '';
    
                 
                } else {
                    // Le score est inférieur à 0.7, la validation est rejetée
                    require_once 'src/views/login.php';
                }
                require_once 'src/views/login.php';
            }
            require_once 'src/views/login.php';
        }

        public function handlePasswordReset() {
            $token = $_GET['token'];
        
            // Vérifiez le jeton et la date d'expiration
            $dao = new DaoAppli(); 
            $user = $dao->getUserByToken($token);
        
            if (!$user) {
                echo "Jeton invalide.";
                return;
            }
        
            $currentDate = new DateTime();
            $tokenExpiration = new DateTime($user['reset_token_expiration']);
        
            if ($currentDate > $tokenExpiration) {
                echo "Le jeton a expiré.";
                return;
            }
        
            // Affichez un formulaire pour saisir un nouveau mot de passe
            // Lorsque l'utilisateur soumet le formulaire, mettez à jour le mot de passe dans la base de données
        }

        public function resetPassword() {
            if (!empty($_POST['nom_mail'])) {
                $nomMail = htmlspecialchars($_POST['nom_mail']);
                $dao = new DaoAppli();
                
                // Vérifiez si l'entrée existe dans la base de données
                $existsInDatabase = $dao->checkUserExists($nomMail);
                
                if ($existsInDatabase) {
                    // L'utilisateur existe dans la base de données, générez le jeton de réinitialisation
                    $resetResult = $dao->resetMdp($nomMail);  
                    
        
                    if ($resetResult) {
                        // Réinitialisation réussie, informez l'utilisateur
                        echo "Un e-mail de réinitialisation a été envoyé. Veuillez vérifier votre boîte de réception.";
                    } else {
                        // Échec de la réinitialisation, informez l'utilisateur de l'erreur
                        echo "La réinitialisation du mot de passe a échoué. Veuillez vérifier les informations fournies.";
                    }
                } else {
                    // L'entrée n'existe pas dans la base de données, affichez un message d'erreur
                    echo "L'utilisateur n'existe pas dans la base de données. Veuillez vérifier les informations fournies.";
                }
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
            $nouveauTitre          =     htmlspecialchars($_POST['titre'], ENT_QUOTES, 'UTF-8');
            $nouveauBackground     =     htmlspecialchars($_POST['background'], ENT_QUOTES, 'UTF-8');
            $nouvelleCouleurTitre  =     htmlspecialchars($_POST['titre_color'], ENT_QUOTES, 'UTF-8');
            $nouvelleFontFamily    =     htmlspecialchars($_POST['titre_font_family']);
            $nouvelleFontSizeGrand =     htmlspecialchars($_POST['titre_font_size_grand_ecran'], ENT_QUOTES, 'UTF-8');
            $nouvelleFontSizeMoyen =     htmlspecialchars($_POST['titre_font_size_moyen_ecran'], ENT_QUOTES, 'UTF-8');
            $nouvelleFontSizePetit =     htmlspecialchars($_POST['titre_font_size_petit_ecran'], ENT_QUOTES, 'UTF-8');
            
            // Mettez à jour le fond d'écran de la page dans DaoAppli
            $dao = new DaoAppli();
            $dao->modifBackgroundTitre($nouveauTitre, $nouveauBackground, $nouvelleCouleurTitre, $nouvelleFontFamily, $nouvelleFontSizeGrand, $nouvelleFontSizeMoyen, $nouvelleFontSizePetit);   
            
            $donneesOrigine = $this->getDonneestable();
            // Initialisez le tableau des informations sur les images
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Un fichier a été sélectionné et il n'y a pas d'erreur
            
                // Informations sur le fichier téléchargé
                $nomFichier = $_FILES['image']['name']; // Nom du fichier image
                $typeFichier = $_FILES['image']['type'];
                $tailleFichier = $_FILES['image']['size'];
                $fichierTemporaire = $_FILES['image']['tmp_name'];
            
                // Vérifier que le fichier est une image ex: le type
                $extensionsAutorisées = array('image/jpeg', 'image/gif', 'image/png');
            
                if (!in_array($typeFichier, $extensionsAutorisées)) {
                    $_SESSION['messageImageError'] = "Seules les images au format JPEG, PNG ou GIF sont autorisées.";
                    header('Location: /admin');
                    exit();
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
            } else {
                // Aucun fichier n'a été sélectionné
                $_SESSION['messageImageError'] = "";
                header('Location: /admin');
                exit();
            }
            header('Location: /admin');
                exit();
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
                            
 

                                // Redirigez l'utilisateur vers la page admin pour recharger la page
                                header('Location: /admin');
                                exit();
                            // Vous pouvez envoyer une réponse JSON si nécessaire
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