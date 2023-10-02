<?php require_once "common/head_admin.php";

// var_dump($_SESSION['nom']);
// var_dump($_SESSION);

// L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion(retour navigateur)
if (!isset($_SESSION['nom'])) {
  header("Location: /login/admin");
  exit;
}
 ?>
<link rel="stylesheet" href="../../assets/css/admin.css">
<title>DECAROLI - ADMIN</title>
</head>
<style>
        #container-form {
         background   : <?= $donneesOrigine[0]['bkgd_color'] ?>;
        }
    </style> 
<body>
<div id="navbar">
    <ul>
        <li><a href="/" target="_blank">Page promo</a></li>
        <?php
        if (isset($_SESSION['nom'])) {
          $nom = $_SESSION['nom'] ?? "";
            echo "<li><a href='/deconnexion'>Se Déconnexion</a></li>";
            echo "<li>Bienvenue " . strtoupper($nom) . "</li>";;
        } else {
          echo 'Les variables de session ont été supprimées.';
         }
        ?>  
    </ul>
</div>

<div id="container-form">
    <form id="form" action="/traitement-formulaire" method="POST" enctype="multipart/form-data">
          <div id="container-titre">
              <label for="titre">Modifier le titre:</label><br>
              <input id="input-titre" type="text" name="titre" value="<?= $donneesOrigine[0]['titre'] ?>"><br>
          </div>
          <div id="container-image">
                <div id="container-ajout-image">
                <input id="input-ajouter-image" type="button" value="Ajouter des images" onclick="chooseFile()">
                <span id="custom-file-label">Aucun fichier sélectionné</span>
                <input type="file" name="image" accept="image/*" id="image" style="display: none;">
                </div>
          </div>
          <div id="container-background">
              <label for="background">Modifier le body:</label><br>
              <input type="text" name="background" value="<?= $donneesOrigine[0]['bkgd_color'] ?>"><br>
          </div> 
         <button id="bouton-form"type ="submit">Valider</button>
    </form>
              <form action="/supprimer-image"></form>
                <div id="container-image-db">
                  <?php foreach ($donneesOrigine as $image) : ?>
                      <div class="image">
                          <img src="../../assets/ressources/images/<?=$image['url'] ?>" alt="<?= $image['nom_image'] ?>">
                          <button class="bouton-supprimer-image" data-id="<?=$image['id_image']?>">Supprimer</button>
                          <button class="bouton-modifier-image">Modifier</button>
                      </div>
                  <?php endforeach; ?>
                  </div>
  </div>



<script src="../../assets/composantJs/admin.js"></script>
<script src="../../assets/composantJs/supprimerImage.js"></script>
</body>
</html>