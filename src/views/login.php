<?php

use DECAROLI\controllers\Messages;
// Change l'id de session et supprime l'ancien
session_regenerate_id(true);
error_reporting(E_ALL);
include "src/config.php";
//début en-tete commun pour toutes les pages
require_once "common/head.php";
?>
<!-- lien pour le css -->
<link rel="stylesheet" href="../../assets/css/login.css">
<script>//variable qui contient la clé api coté client
    var cleApi = "<?php echo $config['recaptcha']['CLE_API_CLIENT_RECAPTCHA']; ?>";
</script><!--connexion a l'api recaptcha de google -->
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $config['recaptcha']['CLE_API_CLIENT_RECAPTCHA']; ?>"></script>
<!-- titre du document pour le navigateur -->
<title>DECAROLI - login</title>
</head>

<body>

    <div id="container-form">
        <img src="../../assets/ressources/images/logoDecaroli.png" alt="logo-decaroli">

        <?php
        $errorMessages = Messages::getMessages();
        if (isset($errorMessages) && !empty($errorMessages)) {
            // Afficher les messages d'erreur
            foreach ($errorMessages as $errorMessage) {
                echo '<div class="message-alert">' . $errorMessage . '</div>';
            }
        } ?>

        <form id="loginForm" action="/connexion" method="post">
            <label for="nom">Identifiant :</label>
            <input type="text" id="nom" name="identifiant">
            <label for="mdp" id="label-mdp">Mot de passe :</label>
            <input type="password" id="input-mdp" name="mdp">
            <input type="button" class="button" value="Se connecter" onclick="initialisationRecaptcha(cleApi,'loginForm')">
            <p id="mdp-oublie">Mot de passe oublié</p>
        </form>

    </div>

    <script src="../../assets/composantJs/login.js"></script>
</body>

</html>


<!-- <form id="resetPassword" method="post" action="/reset-password">
    <label id="label_nom_mail" for="nom_mail">Adresse e-mail :</label>
    <input type="text" name="nom_mail" id="nom_mail" >
    <input type="button" class="button" id="button-reset-mdp" value="Réinitialiser le mot de passe" onclick="initiateRecaptcha('resetPassword')">
    <p id="se-connecter">Se connecter</p>
    </form> -->