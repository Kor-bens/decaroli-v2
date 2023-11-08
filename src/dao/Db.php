<?php

namespace DECAROLI\dao;

use \PDO;

// $filename = 'erreurs.log';

// // Assurez-vous que le fichier erreurs.log existe ou créez-le s'il n'existe pas
// if (!file_exists($filename)) {
//     touch($filename); // Crée le fichier s'il n'existe pas
//     chmod($filename, 0666); // Définit les autorisations du fichier (lecture et écriture)
// }

class Db
{
    private PDO $db; // Déclaration d'une propriété privée pour l'objet PDO

    public function __construct()
    {
        // global $filename;

        // configurer l'objet PDO pour la gestions des erreurs
        $pdoOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
 
        try {
            // Connexion à la base de données MySQL
            include "src/config.php";
            $this->db = new PDO($config['db']['URL'], $config['db']['NOM_UTILISATEUR'], $config['db']['MDP'], $pdoOptions);
           
        } catch (\PDOException $e) {
            // Gestion des erreurs de connexion à la base de données
            echo "- Erreur de connexion PDO : . " . $e->getMessage();
        }
    }

    public function getDb()
    {
        return $this->db; // Retourne l'objet PDO représentant la connexion à la base de données
    }
}
