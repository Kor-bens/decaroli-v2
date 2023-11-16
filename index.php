<?php
//namespace CntrlAppli
use DECAROLI\controllers\CntrlAppli;
session_start();

require_once 'vendor/autoload.php';
ini_set('display_errors', "On");
ini_set('log_errors', "On");
error_reporting(E_ALL);
ob_start();
// Récupére la route dans l'url et la méthode HTTP de la demande
$route = htmlspecialchars(explode("?", $_SERVER['REQUEST_URI'])[0]);
// stocke la méthode HTTP utilisée pour la requête (GET, POST, etc.).
$method = $_SERVER['REQUEST_METHOD'];

// Création d'une instance du contrôleur CntrlAppli
$cntrlAppli = new CntrlAppli();  

// En fonction de la méthode http (GET, POST, PUT, DELETE) et de la route, cela va appeler une methode du controleur
if     ($method == 'GET' && $route == '/index')                     {$cntrlAppli->afficherPagePromo();} 
elseif ($method == 'GET' && $route == '/')                          {$cntrlAppli->afficherPagePromo();} 
elseif ($method == 'GET' && $route == '/login')                     {$cntrlAppli->afficherPageLogin();} 
elseif ($method == 'GET' && $route == '/dash-board')                {$cntrlAppli->afficherPageDashBoard();}  
elseif ($method == 'GET' && $route == '/gestion-user')              {$cntrlAppli->afficherPageGestionUser();}     
elseif ($method == 'POST' && $route == '/traitement-ajout-user')    {$cntrlAppli->traitementAjoutUser();}    
elseif ($method == 'DELETE' && $route == '/traitement-supprimer-user'){$cntrlAppli->supprimerUser();}    
elseif ($method == 'POST' && $route == '/traitement-modifier-user'){$cntrlAppli->modifierUser();}    
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
