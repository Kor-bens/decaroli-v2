<?php 
require_once "common/head_admin.php";
?>

<link rel="stylesheet" href="../../assets/css/login.css?v=<?= time(); ?>">
<head>
<script src="https://www.google.com/recaptcha/api.js?render=6Lfkds8oAAAAAPRPPv2yTGlZAECdMaHA-jMqnkon"></script>

<title>DECAROLI - login</title>
</head>

<body>

<div id="container-form">        
<img src="../../assets/ressources/images/logoDecaroli.png" alt="logo-decaroli">
    <!-- <?php 
     echo 'ID de session (récupéré via $_COOKIE) : ' . $_COOKIE['PHPSESSID'];
    ?> -->

    <?php if (!empty(Message::getErrorMessage())): ?>
        <div id="container-message">
            <?php foreach (Message::getErrorMessage() as $err): ?>
                <div class="message-alert"><?= $err ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?> 
        <?php if (!empty($errorMessage)): ?>
   
        <?php foreach ($errorMessage as $error): ?>
            <div class="message-alert"> <?= $error ?></div>
        <?php endforeach; ?>
    
<?php endif; ?>

    <form id="loginForm" action="/connexion" method="post">
        <label for="nom">Nom d'utilisateur :</label>
        <input type="text" id="nom" name="nom" autocomplete="nom" >
        <label for="mdp" id="label-mdp">Mot de passe :</label>
        <input type="password" id="input-mdp" name="mdp" autocomplete="mdp" >
        <input type="submit" class="button" value="Se connecter"
        class="g-recaptcha" 
        data-sitekey="6Lfkds8oAAAAAPRPPv2yTGlZAECdMaHA-jMqnkon" 
        data-callback='onSubmit' 
        data-action='submit'>     
       <p id="mdp-oublie">Mot de passe oublié</p>     
       <input type="hidden" name="g-recaptcha-response" value="">    
    </form>

    
     
    
    <form id="resetPassword" method="post" action="/reset-password">
    <label id="label_nom_mail" for="nom_mail">Adresse e-mail :</label>
    <input type="text" name="nom_mail" id="nom_mail" >
    <input type="submit" class="button" id="button-reset-mdp" value="Réinitialiser le mot de passe">
    <p id="se-connecter">Se connecter</p>
    </form>
    <input type="hidden" name="g-recaptcha-response" value="">
</div>


<script>
   function onSubmit(token) {
     document.getElementById("demo-form").submit();
   }
 </script>

<script src="../../assets/composantJs/login.js"></script>
</body>
</html>