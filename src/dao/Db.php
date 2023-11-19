<?php
namespace DECAROLI\dao;
use \PDO;

class Db
{
    // Déclaration d'une propriété privée pour l'objet PDO
    protected PDO $db; 

    public function __construct()
    {
        // configuration de l'objet PDO pour la gestions des erreurs
        $pdoOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
 
        try {
            // Connexion à la base de données MySQL
            include "src/config.php";
            $this->db = new PDO($config['db']['URL'], $config['db']['NOM_UTILISATEUR'], $config['db']['MDP']);
           //Capture l'extension 
        } catch (\PDOException $e) {
            //Afficher le message d'erreur de connexion à la base de données
            error_log($e->getMessage());
        }
    }
    // Methode pour permettre la connexion a la base de données
    public function getDb()
    {
        // Retourne l'objet PDO représentant la connexion à la base de données
        return $this->db; 
    }
}
