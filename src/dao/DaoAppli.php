<?php 
require_once 'src/controllers/Message.php';
require_once 'src/dao/Db.php';
require_once 'src/dao/Requete.php';
require_once 'src/models/Admin.php';
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'path/to/PHPMailer/PHPMailer.php';
// require 'path/to/PHPMailer/Exception.php';
// require 'path/to/PHPMailer/SMTP.php';

class DaoAppli{

    private PDO $db;
    public function __construct() {
        $dbObjet  = new Db(); 
        $this->db = $dbObjet->getDb();
        // Activez le mode d'erreur PDO
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    private function logError($errorMessage) {
        global $filename;
        // Spécifiez le fuseau horaire que vous souhaitez utiliser
        date_default_timezone_set('Europe/Paris'); // Remplacez 'Europe/Paris' par le fuseau horaire de votre choix
        $timestamp = date('d-m-Y H:i:s'); // Obtenez la date et l'heure actuelles au format "Y-m-d H:i:s"
        $fichier   =  __FILE__ ;
        $logEntry = "$timestamp $fichier - Erreur dans DaoAppli : $errorMessage"  . PHP_EOL;
        error_log($logEntry, 3, $filename, FILE_APPEND);
    }
    private function getAdminByNom($nom): array {
        try {
            $requete = Requete::REQ_NOM_ADMIN;
            $stmt = $this->db->prepare($requete);
            $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
                return $resultat;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            $this->logError('Erreur PDO dans getAdminByNom : ' . $e->getMessage());
            return [];
        }
    }
    public function connexionUser($nom, $mdp) {
        // Réinitialisez la variable d'erreur à chaque nouvelle tentative de connexion
        $errorMessage = [];
        
        try {
            //si il existe et Nettoyez le nom d'utilisateur en supprimant les espaces inutiles et en évitant les caractères spéciaux
            if (isset($_POST['nom']) && isset($_POST['mdp'])) {
                // $nom = isset($_POST['nom']) ? trim(addcslashes(strip_tags($_POST['nom']), '\x00..\x1F')) : '';
                // $mdp = isset($_POST['mdp']) ? trim(addcslashes(strip_tags($_POST['mdp']), '\x00..\x1F')) : '';
                $nom = isset($_POST['nom']) ? htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8') : '';
                $mdp = isset($_POST['mdp']) ? htmlspecialchars($_POST['mdp'], ENT_QUOTES, 'UTF-8') : '';
                $mdp = hash('sha512', $mdp);
    
                $admin = $this->getAdminByNom($nom);
    
                if ($admin && $admin['mdp'] === $mdp) {
                    // Les informations de connexion sont correctes
                    // Stockez le nom d'utilisateur dans la session
                    $_SESSION['nom'] = $nom;
                    header('Location: /admin');
                    exit; // Assurez-vous de quitter le script après la redirection
                } else {
                    $errorMessage[] = Message::ERR_LOGIN;
                    // Enregistrez cette erreur dans le journal
                    $this->logError('Échec de la connexion utilisateur : identifiants incorrects');
                }
            }
        } catch (PDOException $e) {
            $this->logError('Erreur PDO dans connexionUser  : ' . $e->getMessage());
        }
    
        return $errorMessage;
    }

    public function resetMdp($nomMail) {
        try {
            // Préparez la requête SQL
            $requete = Requete::REQ_NOM_MAIL_ADMIN;
            $stmt = $this->db->prepare($requete);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
        
            // Exécutez la requête
            $stmt->execute();
        
            // Récupérez le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($result) {
                // Le nom d'utilisateur et l'adresse e-mail correspondent à un enregistrement dans la base de données
                // Vous pouvez générer un jeton de réinitialisation et l'envoyer par e-mail à l'utilisateur ici
                
                // Générez un jeton de réinitialisation
                $resetToken = bin2hex(random_bytes(32));
                
                // Définissez la date d'expiration (24 heures à partir de maintenant)
                $resetTokenExpiration = date('Y-m-d H:i:s', strtotime('+24 hours'));
                
                // Mettez à jour la base de données avec le jeton et la date d'expiration
                $sql = "UPDATE administrateur SET reset_token = :resetToken, reset_token_expiration = :resetTokenExpiration WHERE nom = :nom";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':resetToken', $resetToken, PDO::PARAM_STR);
                $stmt->bindParam(':resetTokenExpiration', $resetTokenExpiration, PDO::PARAM_STR);
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->execute();
                
                // Envoyez un e-mail contenant le lien de réinitialisation à l'adresse e-mail de l'utilisateur
                $sujet = "Réinitialisation de mot de passe";
                $message = "Bonjour, vous avez demandé une réinitialisation de mot de passe. Veuillez cliquer sur le lien suivant pour réinitialiser votre mot de passe : http://votresite.com/reset-password?token=$resetToken";
                $headers = "From: votreadresse@votresite.com";
                
                if (mail($mail, $sujet, $message, $headers)) {
                    return true; // L'e-mail a été envoyé avec succès
                } else {
                    // Gestion des erreurs d'envoi d'e-mail
                    return false;
                }
            }
        } catch (PDOException $e) {
            // Gérez les erreurs PDO ici
            $errorMessage = 'Erreur PDO dans resetMdp : ' . $e->getMessage();
        $this->logError($errorMessage);
        error_log($errorMessage);
        }
    }

    public function checkUserExists($nomMail) {
        try {
            $requete = "SELECT COUNT(*) FROM administrateur WHERE nom = :nom OR mail = :mail";
            $stmt = $this->db->prepare($requete);
            $stmt->bindParam(':nom', $nomMail, PDO::PARAM_STR);
            $stmt->bindParam(':mail', $nomMail, PDO::PARAM_STR);
            $stmt->execute();
            
            // Récupérez le nombre d'enregistrements correspondants
            $count = $stmt->fetchColumn();
            
            return $count > 0;
        } catch (PDOException $e) {
            // Gérez les erreurs PDO ici
            $errorMessage = 'Erreur PDO dans checkUserExists : ' . $e->getMessage();
            $this->logError($errorMessage);
            error_log($errorMessage);
            return false;
        }
    }

   

    public function modifBackgroundTitre($nouveauTitre, $nouveauBackground, $nouvelleCouleurTitre, $nouvelleFontFamily, $nouvelleFontGrand, $nouvelleFontSizeMoyen, $nouvelleFontSizePetit) {
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

    public function getTitreCouleurImage() {
        $requete  = Requete::REQ_TITRE_COULEUR_IMAGE;
        
        try {
            $stmt = $this->db->query($requete);
        } catch (PDOException $e) {
            // En cas d'erreur PDO lors de l'exécution de la requête, enregistrez l'erreur dans le journal
            $this->logError('Erreur PDO dans getTitreCouleurImage  : ' . $e->getMessage());
            return [];
        }
        
        $donneesOrigine = [];
        
        while ($resultat = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $donneesOrigine[] = [
                'titre'                         => $resultat['titre'],
                'titre_color'                   => $resultat['titre_color'],
                'titre_font_family'             => $resultat['titre_font_family'],
                'titre_font_size_grand_ecran'   => $resultat['titre_font_size_grand_ecran'],
                'titre_font_size_moyen_ecran'   => $resultat['titre_font_size_moyen_ecran'],
                'titre_font_size_petit_ecran'   => $resultat['titre_font_size_petit_ecran'],
                'bkgd_color'                    => $resultat['bkgd_color'],
                'nom_image'                     => $resultat['nom_image'],
                'url'                           => $resultat['url'],
                'id_image'                      => $resultat['id_image']
            ];
        }
    
        return $donneesOrigine;
    }
    
    public function traitementImage($nouvelleImage, $nomFichier, $idPage) {
        $requete = Requete::REQ_AJOUT_IMAGE;
        
        try {
            $stmt = $this->db->prepare($requete);
            $stmt->bindParam(':url', $nouvelleImage);
            $stmt->bindParam(':nom_image', $nomFichier);
            $stmt->bindParam(':id_page', $idPage);
            $stmt->execute();
        } catch (PDOException $e) {
            // En cas d'erreur PDO lors de l'exécution de la requête, enregistrez l'erreur dans le journal
            $this->logError('Erreur PDO dans traitementImage  : ' . $e->getMessage());
        }
    }
    
    public function supprimerImage($idImage) {
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
    
    public function modifierImage($nouveauNomImage, $nouvelleUrl, $idImageModifier) {
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
    
    public function getNomFichierImageById($idImage) {
        $requete = "SELECT url FROM image WHERE id_image = :id";
        
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



    
