<?php 
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

ini_set('session.cookie_secure', 1); // Utiliser des cookies de session uniquement via HTTPS
ini_set('session.cookie_httponly', 1); // Empêcher l'accès aux cookies de session via JavaScript

session_start();
session_regenerate_id(true);



require_once 'src/dao/DaoAppli.php';
require_once 'src/controllers/CntrlAppli.php';
require_once 'src/controllers/Message.php';
require_once "src/dao/Requete.php";

$route = htmlspecialchars(explode("?", $_SERVER['REQUEST_URI'])[0]);
$method = $_SERVER['REQUEST_METHOD'];

$cntrlAppli = new CntrlAppli(); 

if      ($method == 'GET'    && $route == '/index')                      $cntrlAppli->afficherPagePromo();
else if ($method == 'GET'    && $route == '/')                           $cntrlAppli->afficherPagePromo();
else if ($method == 'GET'    && $route == '/login')                      $cntrlAppli->afficherPageLogin(); 
else if ($method == 'GET'    && $route == '/admin')                      $cntrlAppli->afficherPageAdmin(); 
else if ($method == 'POST'   && $route == '/connexion')                  $cntrlAppli->connexion(); 
else if ($method == 'GET'    && $route == '/deconnexion')                $cntrlAppli->deconnexion();   
else if ($method == 'POST'   && $route == '/traitement-formulaire')      $cntrlAppli->traitementFormulaire();    
else if ($method == 'DELETE' && $route == '/supprimer-image')            $cntrlAppli->supprimerImage();    
else if ($method == 'POST'   && $route == '/modifier-image')             $cntrlAppli->modifierImage();      
  


else{
    header("Location: /index");
    exit;
}