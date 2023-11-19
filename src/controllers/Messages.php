<?php  
namespace DECAROLI\controllers;
class Messages {
    
private static $sessionKey = "flash_message";


 public static function addMessage($message) {
    if (!isset($_SESSION[self::$sessionKey])) {
        $_SESSION[self::$sessionKey] = [];
    }
    $_SESSION[self::$sessionKey][] = $message;
}

// Récupére tous les messages et les effacer de la session après les avoir récupérés
public static function getMessages() {
    $messages = $_SESSION[self::$sessionKey] ?? [];
    unset($_SESSION[self::$sessionKey]); // Efface les messages après les avoir récupérés
    return $messages;
}



// Constantes pour les messages
    public const INP_ERR_CHAMP_VIDE = "Veuillez remplir tous les champs";
    public const ERR_LOGIN = "Nom d'utilisateur ou mot de passe incorrect";
    public const INP_ERR_NOM = "Veuillez remplir le champ Nom d'utilisateur";
    public const INP_ERR_MDP = "Veuillez remplir le champ Mot de passe";
    public const INP_ERR_NOM_CHAR = "Le nom d'utilisateur doit contenir au moins 4 caractères.";
    public const INP_ERR_MDP_CHAR = "Le mot de passe doit contenir au moins 8 caractères.";
    public const INP_ERR_MDP_CHAR_SPE = "Le mot de passe doit contenir au moins un caractère spécial.";
    public const ERR_LOGIN_ACCES_PAGE = "Vous n'avez pas de droit d'accès sur cette page";
    public const ERR_SYSTEM = "Une erreur système est survenue";
}
