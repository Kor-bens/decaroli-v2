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
            $_SESSION['errorMessage'] = $errorMessage;
            $nom = $_POST['nom'] ?? null;
            $mdp = $_POST['mdp'] ?? null;
        
            if (empty($nom) || empty($mdp)) {
                $errorMessage[] = Message::INP_ERR_CHAMP_VIDE;
                $_SESSION['errorMessage'] = $errorMessage;
                header('Location: /login');
            }
        
            $dao = new DaoAppli();
            $errorMessageFromDao = $dao->connexionUser($nom, $mdp);
        
            // Ajoutez les messages d'erreur du DaoAppli aux messages d'erreur existants
             $errorMessage = array_merge($errorMessage, $errorMessageFromDao);
             $_SESSION['errorMessage'] = $errorMessage;
            // Message::setErrorMessage($errorMessage);
        
            if (!empty($_POST['nom']) && !empty($_POST['mdp'])) {
                $secret = "6Lfkds8oAAAAACgUwWz39ekeIDucyKmPvKfuerP5";
                $response = htmlspecialchars($_POST['g-recaptcha-response']);
                $remoteip = $_SERVER['REMOTE_ADDR'];
                $request  = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
        
                $get  = file_get_contents($request);
                $decode = json_decode($get, true);
                
                if ($decode['success'] && $decode['score'] >= 0.9) {
                    // Le score est supérieur ou égal à 0.7, la validation est réussie
                    // Redirigez l'utilisateur vers la page admin
                    var_dump($decode);
                    header('Location: /admin');
                    exit; 
                } else {
                    // Le score est inférieur à 0.7, la validation est rejetée
                    $errorMessage[] = "Votre tentative de connexion a été rejetée en raison d'une activité suspecte détectée";
                    var_dump($decode);
                    $_SESSION['errorMessage'] = $errorMessage;  // Stockez les messages d'erreur dans la session
                    header('Location: /login');
                }
                require_once 'src/views/login.php';
            }
        
            // Restez dans la même vue pour afficher les messages d'erreur
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
                    echo "id existant";
        
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

        // function redimensionnerImage($cheminSource, $cheminCible, $largeurMax, $hauteurMax) {
        //     // Récupérez les dimensions originales de l'image
        //     list($largeurOrig, $hauteurOrig) = getimagesize($cheminSource);
        
        //     // Calculez le ratio pour redimensionner l'image
        //     $ratio = min($largeurMax / $largeurOrig, $hauteurMax / $hauteurOrig);
        //     $nouvelleLargeur = round($largeurOrig * $ratio);
        //     $nouvelleHauteur = round($hauteurOrig * $ratio);
        
        //     // Créez une nouvelle image avec les nouvelles dimensions
        //     $nouvelleImage = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);
        
        //     // Récupérez l'image source
        //     $imageSource = imagecreatefromjpeg($cheminSource); // Changez cette fonction en fonction du type d'image
        
        //     // Redimensionnez l'image
        //     imagecopyresampled($nouvelleImage, $imageSource, 0, 0, 0, 0, $nouvelleLargeur, $nouvelleHauteur, $largeurOrig, $hauteurOrig);
        
        //     // Sauvegardez l'image redimensionnée
        //     imagejpeg($nouvelleImage, $cheminCible, 100); // Vous pouvez changer la qualité si nécessaire
        
        //     // Libérez la mémoire
        //     imagedestroy($nouvelleImage);
        //     imagedestroy($imageSource);
        // }

        public function traitementFormulaire() {
            // Fonction pour redimensionner une image
            function redimensionnerImage($cheminSource, $cheminCible, $largeurMax, $hauteurMax) {
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
            
            $donneesOrigine = $this->getDonneestable();
            
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
                redimensionnerImage($fichierTemporaire, $cheminStockage, $largeurMax, $hauteurMax);
                
                $idPage = 1; // Remplacez par l'ID de la page appropriée
                $dao->traitementImage($nomUnique, $nomFichier, $idPage);
                echo $nomFichier;
            }
            header('Location: /admin');
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
        
            // Fonction pour redimensionner une image
            function redimensionnerImageModifier($cheminSource, $cheminCible, $largeurMax, $hauteurMax) {
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
        
                                imagedestroy($nouvelleImage);
                                imagedestroy($imageSource);
                } else {
                    echo "Le fichier source n'existe pas : " . $cheminSource;
                }
            }
        
            // Votre code de traitement du formulaire ici...
            // ...
        
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
                            redimensionnerImage($cheminStockage, $cheminImageRedimensionnee, $largeurMax, $hauteurMax);
        
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
