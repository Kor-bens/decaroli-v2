<?php
// Définissez les paramètres de session avant de démarrer la session
ini_set('session.cookie_secure', 0); // Désactivez l'utilisation de cookies de session uniquement via HTTPS
ini_set('session.cookie_httponly', 1); // Empêchez l'accès aux cookies de session via JavaScript

session_start();
session_regenerate_id(true);
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'dao/DaoAppli.php';
require_once 'controllers/CntrlAppli.php';
require_once 'controllers/Message.php';
require_once "dao/Requete.php";

$delaiInactivite = 5; // Délai d'inactivité en secondes

if (isset($_SESSION['derniereActivite'])) {
    $tempsActuel = time();
    $derniereActivite = $_SESSION['derniereActivite'];

    // Vérifiez si le délai d'inactivité est dépassé
    if ($tempsActuel - $derniereActivite > $delaiInactivite) {
        // L'utilisateur est inactif, déconnectez-le
        session_unset();
        session_destroy();
        header('Location: /login'); // Redirigez l'utilisateur vers la page de connexion
        exit;
    }
}

// Mettez à jour le timestamp de la dernière activité à chaque action de l'utilisateur
$_SESSION['derniereActivite'] = time();