<?php
$filename = 'erreurs.log';

// Assurez-vous que le fichier erreurs.log existe ou créez-le s'il n'existe pas
if (!file_exists($filename)) {
    touch($filename); // Crée le fichier s'il n'existe pas
    chmod($filename, 0666); // Définit les autorisations du fichier (lecture et écriture)
}

class Db {
    private PDO $db; // Déclaration d'une propriété privée pour l'objet PDO

    public function __construct() {
        global $filename;

        // configurer l'objet PDO pour la gestions des erreurs
        $pdoOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            // Connexion à la base de données MySQL
            $this->db = new PDO('mysql:host=localhost;dbname=decaroli;charset=utf8', 'korbens', 'Toto1234.', $pdoOptions);
        } catch (PDOException $e) {
            // Gestion des erreurs de connexion à la base de données
            // Spécifiez le fuseau horaire que vous souhaitez utiliser
            date_default_timezone_set('Europe/Paris'); // Remplacez 'Europe/Paris' par le fuseau horaire de votre choix

            // Obtenez la date et l'heure actuelles au format "Y-m-d H:i:s"
            $timestamp = date('d-m-Y H:i:s');

            $fichier =  __FILE__ ; // Obtenez le nom du fichier en cours d'exécution

            // Créez l'entrée de journal avec l'horodatage et l'erreur PDO
            $errorLogEntry = "$timestamp $fichier - Erreur de connexion PDO : " . $e->getMessage() . PHP_EOL;

            // Enregistrez le message d'erreur dans un fichier journal sans l'afficher
            error_log($errorLogEntry, 3, $filename, FILE_APPEND);
        }
    }

    public function getDb() {
        return $this->db; // Retourne l'objet PDO représentant la connexion à la base de données
    }
}