<?php
use DECAROLI\controllers\CntrlAppli;
// Configurez les paramètres de session avant de démarrer la session
ini_set('session.cookie_secure', 0); // Désactivez l'utilisation de cookies de session uniquement via HTTPS
ini_set('session.cookie_httponly', 1); // Empêchez l'accès aux cookies de session via JavaScript

// Démarrez la session
session_start();
session_regenerate_id(true);
ob_start();
require_once 'vendor/autoload.php';



// Récupérez la route et la méthode HTTP de la demande
$route = htmlspecialchars(explode("?", $_SERVER['REQUEST_URI'])[0]);
$method = $_SERVER['REQUEST_METHOD'];

// Créez une instance du contrôleur CntrlAppli
$cntrlAppli = new CntrlAppli();  

// En fonction de la méthode HTTP et de la route, appelez les méthodes appropriées du contrôleur
if ($method == 'GET' && $route == '/index')                         {$cntrlAppli->afficherPagePromo();} 
elseif ($method == 'GET' && $route == '/')                          {$cntrlAppli->afficherPagePromo();} 
elseif ($method == 'GET' && $route == '/login')                     {$cntrlAppli->afficherPageLogin();} 
elseif ($method == 'GET' && $route == '/admin')                     {$cntrlAppli->afficherPageAdmin();} 
elseif ($method == 'POST' && $route == '/connexion')                {$cntrlAppli->connexion();} 
elseif ($method == 'GET' && $route == '/deconnexion')               {$cntrlAppli->deconnexion();} 
elseif ($method == 'POST' && $route == '/traitement-formulaire')    {$cntrlAppli->traitementFormulaire();} 
elseif ($method == 'DELETE' && $route == '/supprimer-image')        {$cntrlAppli->supprimerImage();} 
elseif ($method == 'POST' && $route == '/modifier-image')           {$cntrlAppli->modifierImage();} 
else {
    // Si la route n'est pas gérée, redirigez vers la page d'accueil
    header("Location: /index");
    exit;
}