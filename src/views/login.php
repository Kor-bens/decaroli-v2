<?php 
require_once "common/head_admin.php";
?>

<link rel="stylesheet" href="../../assets/css/login.css?v=<?= time(); ?>">
<head>
<title>DECAROLI - login</title>
</head>

<body>

<div id="container-form">        
<img src="../../assets/ressources/images/logoDecaroli.png" alt="logo-decaroli">
    <!-- <?php 
     echo 'ID de session (récupéré via $_COOKIE) : <br>' . $_COOKIE['PHPSESSID'];
    ?> -->

    <?php if (!empty(Message::getErrorMessage())): ?>
        <div id="container-message">
            <?php foreach (Message::getErrorMessage() as $err): ?>
                <div class="message-alert"><?= $err ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?> 
        <?php if(isset($errorMessageVide)): ?>
            <div class="message-alert"><?= $errorMessageVide ?></div>
        <?php endif; ?>

    <form id="loginForm" action="/connexion" method="post" onsubmit="onClick(event)">
        <label for="nom">Nom d'utilisateur :</label>
        <br>
        <input type="text" id="nom" name="nom" >
        <br><br>
        <label for="mdp">Mot de passe :</label>
        <br>
        <input type="password" id="mdp" name="mdp" >
        <br><br>
        <input type="submit" class="button" value="Se connecter"
                class="g-recaptcha" 
                data-sitekey="6LfFS8IoAAAAAH0O-iZO0vm4pnkc-EHwM7icG-Vi" 
                data-callback='onSubmit' 
                data-action='submit'>     
       <p id="mdp-oublie">Mot de passe oublié</p>         
    </form>

    
     
    <br>
    <form id="resetPassword" method="post" action="/reset-password">
    <label id="label_nom_mail" for="nom_mail">Adresse e-mail :</label>
    <br>
    <input type="text" name="nom_mail" id="nom_mail" required>
    <br><br>
    <input type="submit" class="button" value="Réinitialiser le mot de passe">
    <p id="se-connecter">Se connecter</p>
    </form>
    
</div>


<script>
   function onSubmit(token) {
     document.getElementById("loginForm").submit();
   }
 </script>

<script src="https://www.google.com/recaptcha/api.js"></script>
<script src="../../assets/composantJs/login.js"></script>
</body>
</html>