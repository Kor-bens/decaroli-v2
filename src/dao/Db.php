<?php
namespace DECAROLI\dao;

use \PDO;

class Db
{
    // Déclaration d'une propriété privée pour l'objet PDO
    private PDO $db; 

    public function __construct()
    {
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
        // Retourne l'objet PDO représentant la connexion à la base de données
        return $this->db; 
    }
}
