<?php  

class Message {
    private static $errorMessage = "";

    public static function setErrorMessage($message) {
        self::$errorMessage = $message;
    }

    public static function getErrorMessage() {
        return self::$errorMessage;
    }
    public const INP_ERR_CHAMP_VIDE = "Veuillez remplir tous les champs";
    public const ERR_LOGIN = "Nom d'utilisateur ou mot de passe incorrect";
    public const INP_ERR_NOM = "Veuillez remplir le champ Nom d'utilisateur";
    public const INP_ERR_MDP = "Veuillez remplir le champ Mot de passe";

    public const INP_ERR_NOM_CHAR = "Le nom d'utilisateur doit contenir au moins 4 caractères.";
    public const INP_ERR_MDP_CHAR = "Le mot de passe doit contenir au moins 8 caractères.";
    public const INP_ERR_MDP_CHAR_SPE = "Le mot de passe doit contenir au moins 8 caractères.";

}