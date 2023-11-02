<?php 
use DECAROLI\controllers\Messages;
require_once "common/head.php";
?>
<?php if (isset($_SESSION['errorMessage'])) {
    $errorMessage = $_SESSION['errorMessage'];
   } ?>

<link rel="stylesheet" href="../../assets/css/login.css">
<head>
<script src="https://www.google.com/recaptcha/api.js?render=6Lfkds8oAAAAAPRPPv2yTGlZAECdMaHA-jMqnkon"></script>
<title>DECAROLI - login</title>
</head>

<body>
<!-- <?php var_dump($errorMessage) ?> -->
<div id="container-form">        
<img src="../../assets/ressources/images/logoDecaroli.png" alt="logo-decaroli">
    <!-- <?php 
     echo 'ID de session (récupéré via $_COOKIE) : ' . $_COOKIE['PHPSESSID'];
    ?> -->

 <!-- <?php if (!empty(Messages::getErrorMessage())): ?>
        <div id="container-message">
            <?php foreach (Messages::getErrorMessage() as $err): ?>
                <div class="message-alert"><?= $err ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>   -->
        <?php if (!empty($errorMessage)): ?>
   
        <?php foreach ($errorMessage as $error): ?>
            <div class="message-alert"> <?= $error ?></div>
        <?php endforeach; ?>
    
<?php endif; ?>

    <form id="loginForm" action="/connexion" method="post">
        <label for="nom">Identifiant :</label>
        <input type="text" id="nom" name="identifiant" autocomplete="identifiant" >
        <label for="mdp" id="label-mdp">Mot de passe :</label>
        <input type="password" id="input-mdp" name="mdp" autocomplete="mdp" >
        <input type="button" class="button" value="Se connecter" onclick="initiateRecaptcha('loginForm')">   
       <p id="mdp-oublie">Mot de passe oublié</p>     
    </form>

    
     
    
    <form id="resetPassword" method="post" action="/reset-password">
    <label id="label_nom_mail" for="nom_mail">Adresse e-mail :</label>
    <input type="text" name="nom_mail" id="nom_mail" >
    <input type="button" class="button" id="button-reset-mdp" value="Réinitialiser le mot de passe" onclick="initiateRecaptcha('resetPassword')">
    <p id="se-connecter">Se connecter</p>
    </form>

</div>


<script src="../../assets/composantJs/login.js"></script>
</body>
</html>