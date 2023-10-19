<?php 
require_once "common/head_admin.php";
?>

<link rel="stylesheet" href="../../assets/css/login.css?v=<?= time(); ?>">
<head>
<title>DECAROLI - login</title>
</head>

<body>

<div id="container-form">        
    <h1>Connexion</h1>

 

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
        <input type="submit" id="button" value="Se connecter"
                class="g-recaptcha" 
                data-sitekey="6LfTu7MoAAAAABWMtlKV1GSN3cI4-stGVs63-qe8" 
                data-callback='onSubmit' 
                data-action='submit'> 
    </form>
    
</div>


<script>
   function onSubmit(token) {
     document.getElementById("loginForm").submit();
   }
 </script>

<script src="https://www.google.com/recaptcha/api.js"></script>
</body>
</html>