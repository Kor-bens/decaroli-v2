<?php
//namespace CntrlAppli
use DECAROLI\controllers\CntrlAppli;
// Configurez les paramètres de session avant de démarrer la session
ini_set('session.cookie_secure', 0); // Activez l'utilisation de cookies de session uniquement via HTTPS
ini_set('session.cookie_httponly', 1); // Empêchez l'accès aux cookies de session via JavaScript

// Démarrez la session
session_start();
// Change l'id de session et supprime l'ancien
session_regenerate_id(true);
ob_start();
require_once 'vendor/autoload.php';



// Récupére la route dans l'url et la méthode HTTP de la demande
$route = htmlspecialchars(explode("?", $_SERVER['REQUEST_URI'])[0]);
// stocke la méthode HTTP utilisée pour la requête (GET, POST, etc.).
$method = $_SERVER['REQUEST_METHOD'];

// Création d'une instance du contrôleur CntrlAppli
$cntrlAppli = new CntrlAppli();  

// En fonction de la méthode HTTP et de la route, cela va appeler une methode du controleur
if     ($method == 'GET' && $route == '/index')                     {$cntrlAppli->afficherPagePromo();} 
elseif ($method == 'GET' && $route == '/')                          {$cntrlAppli->afficherPagePromo();} 
elseif ($method == 'GET' && $route == '/login')                     {$cntrlAppli->afficherPageLogin();} 
elseif ($method == 'GET' && $route == '/dash-board')                {$cntrlAppli->afficherPageDashBoard();}  
elseif ($method == 'POST' && $route == '/connexion')                {$cntrlAppli->connexion();} 
elseif ($method == 'GET' && $route == '/deconnexion')               {$cntrlAppli->deconnexion();} 
elseif ($method == 'POST' && $route == '/traitement-formulaire')    {$cntrlAppli->traitementFormulaire();} 
elseif ($method == 'DELETE' && $route == '/supprimer-image')        {$cntrlAppli->supprimerImage();} 
elseif ($method == 'POST' && $route == '/modifier-image')           {$cntrlAppli->modifierImage();} 
else {
    // Si la route n'est pas gérée, redirige vers la page d'accueil
    header("Location: /index");
    exit;
}
