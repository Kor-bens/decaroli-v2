<?php
require_once 'vendor/autoload.php';
use DECAROLI\dao\DaoAppli;
use DECAROLI\models\Role;
use DECAROLI\models\Utilisateur;

$dao = new DaoAppli();

$defaultValues = array("nom" => "", "role" =>"Client");
$givenArguments = getopt("", array("nom:", "mail:", "mdp:", "role:"));
$options = array_merge($defaultValues, $givenArguments);
$nom = $options['nom'];
$mail = $options['mail'];
$mdp = $options['mdp'];
$nom_role = $options['role'];

$role = $dao->recuperatioRole($nom_role);


$nom = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');
$mdp = htmlspecialchars($mdp, ENT_QUOTES, 'UTF-8');
$mdp = hash('sha512', $mdp);
// $dao = new DaoAppli();
// $utilisateur = $dao->recuperationUser(5);
echo $nom . PHP_EOL;
echo $mail . PHP_EOL;
echo $mdp . PHP_EOL;
// echo $role . PHP_EOL;
// $utilisateur = new Utilisateur(0, $nom, $mail, $mdp, $role)

try{
    $idUtilisateurcree = $dao->ajoutUtilisateur($nom, $mdp, $mail, $role->id_role);
echo $idUtilisateurcree . PHP_EOL; 
}catch(Exception $e) {
    echo "Error!: " . $e->getMessage() . PHP_EOL;
}

?>