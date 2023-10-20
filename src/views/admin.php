<?php require_once "common/head_admin.php";




// var_dump($_SESSION['nom']);
// var_dump($_SESSION);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion(retour navigateur)
if (!isset($_SESSION['nom'])) {
  header("Location: /login");
  exit;
}
$messageImageError = $_SESSION['messageImageError'] ?? ''; // Récupérez le message d'erreur depuis la session
unset($_SESSION['messageImageError']); // Action réussie, supprimez le message d'erreur de la variable de session
$message = $_SESSION['message'] ?? '';

 ?>
<link rel="stylesheet" href="../../assets/css/admin.css">
<title>DECAROLI - ADMIN</title>
</head>
<style>
        /* #container-form {
         background   : <?= $donneesOrigine[0]['bkgd_color'] ?>;
        } */
    </style> 
<body>
<div id="navbar">
    <ul>
        <li><a href="/" target="_blank">Page promo</a></li>
        <?php
        if (isset($_SESSION['nom'])) {
          $nom = $_SESSION['nom'] ?? "";
            echo "<li><a href='/deconnexion'>Se Déconnexion</a></li>";
            // echo "<li>Bienvenue ".strtoupper($nom)."</li>";
        } else {
          echo 'Les variables de session ont été supprimées.';
         }
        ?>  
    </ul>
</div>

<!-- <div id="administrateur"><h1>Administrateur decaroli <?php echo ucfirst($nom) ?></h1></div> -->

<div id="container-form">
    <form id="form" action="/traitement-formulaire" method="POST" enctype="multipart/form-data">
          <div id="container-titre">
              <label for="titre">Modifier le titre:</label>
              <input id="input-titre" type="text" name="titre" value="<?= $donneesOrigine[0]['titre'] ?>">
              <label id="titre-color" for="titre_color">Modifier la couleur du titre:</label>
              <input id="input-titre-color" type="text" name="titre_color" value="<?= $donneesOrigine[0]['titre_color'] ?>">
              <label id="titre_font_family" for="titre_font_family">Modifier la font-family du titre:</label>
              <input id="input-titre-font-family" type="text" name="titre_font_family" value="<?= $donneesOrigine[0]['titre_font_family'] ?>">
              <label id="titre_font_size_grand_ecran" for="titre_font_size_grand_ecran">Modifier la police du titre pour grand ecran:</label>
              <input id="input-titre-font-size_grand_ecran" type="text" name="titre_font_size_grand_ecran" value="<?= $donneesOrigine[0]['titre_font_size_grand_ecran'] ?>">
              <label id="titre_font_size_grand_ecran" for="titre_font_size_grand_ecran">Modifier la police du titre pour moyen ecran:</label>
              <input id="input-titre_font_size_moyen_ecran" type="text" name="titre_font_size_moyen_ecran" value="<?= $donneesOrigine[0]['titre_font_size_moyen_ecran'] ?>">
              <label id="titre_font_size_petit_ecran" for="titre_font_size_petit_ecran">Modifier la police du titre pour petit ecran:</label>
              <input id="input-titre_font_size_petit_ecran" type="text" name="titre_font_size_petit_ecran" value="<?= $donneesOrigine[0]['titre_font_size_petit_ecran'] ?>">
          </div>

          <div id="container-image">
              <div id="container-ajout-image">
                  <label for="image" id="label-ajouter-image">Ajouter des images</label>
                  <button type="button" id="bouton-ajouter-image">Sélectionner des images</button>
                  <?php if (!empty($messageImageError)) { ?>
                      <p id="message-alert-image"><?php echo $messageImageError; ?></p>
                  
                  <?php } ?>
                  <input id="input-ajouter-image" type="file" name="image" accept="image/*" style="display: none;" multiple>
              </div>
          <!-- Ajoutez un élément img pour afficher l'image sélectionnée -->
               <img id="image-selectionnee" src="" alt="Image sélectionnée" style="display: none;">
          </div>

          <div id="container-background">
              <label for="background">Modifier le body:</label>
              <input type="text" name="background" value="<?= $donneesOrigine[0]['bkgd_color'] ?>"><br>
          </div> 
         <button id="bouton-form"type ="submit">Valider</button>
    </form>
             
                <div id="container-image-db">
                <?php
                // Fonction de comparaison pour trier le tableau par ID croissant
                function compareById($a, $b) {
                    return $a['id_image'] - $b['id_image'];
                }

                // Tri du tableau $donneesOrigine par ID croissant
                usort($donneesOrigine, 'compareById');

                // Initialisez une variable pour suivre la position de l'image
                $position = 0;

                foreach ($donneesOrigine as $image) :
                    // Incrémentez la position à chaque itération
                    $position++;

                    // Appliquez la classe CSS en fonction de la position
                    $class = ($position % 2 === 0) ? 'flex-start' : 'flex-end';
                ?>
                    <div class="image <?= $class ?>">
                        <img src="../../assets/ressources/images/<?= $image['url'] ?>" alt="<?= $image['nom_image'] ?>">
                        <?php if (isset($image['id_image'])) : ?>
                            <button class="bouton-supprimer-image" data-id="<?= $image['id_image'] ?>">Supprimer</button>
                            <!-- Champ de téléchargement de fichier pour la modification de l'image -->
                            <input type="file" class="champ-modifier-image input-image" data-id="<?= $image['id_image'] ?>">
                            <!-- Bouton "Modifier" avec l'attribut onclick -->
                            <button class="bouton-modifier-image" data-id="<?= $image['id_image'] ?>">Modifier</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
                 
  </div>

<!-- <?php
$idImageModifier = $_SESSION['idImageModifier'] ?? '';
$nouveauNomImage = $_SESSION['nouveauNomImage'] ?? '';
$nomUnique       = $_SESSION['nomUnique'] ?? '' ;
$ancienNomImage  = $_SESSION['ancienNom'] ?? '' ;
var_dump($idImageModifier);
var_dump($nouveauNomImage);
var_dump($nomUnique);
var_dump($ancienNomImage);
// Afficher les valeurs où vous le souhaitez dans votre code HTML
echo "ID de l'image modifiée : " . $idImageModifier;?> </br> <?php
echo "Nouveau nom de l'image : " . $nouveauNomImage;?> </br> <?php
echo "nom unique : " . $nomUnique;?> </br> <?php
echo "ancien nom image : " . $ancienNomImage;
 ?>
 <p>Nom Unique : <?php echo $nomUnique ?></p> -->

<?php
// Récupérer le contenu de la mémoire tampon et le stocker dans une variable
$contenuTampon = ob_get_clean();

// Maintenant, vous pouvez envoyer le contenu de la mémoire tampon à la sortie (page)
echo $contenuTampon;
?>


<script src="../../assets/composantJs/admin.js"></script>
<script src="../../assets/composantJs/supprimerModifierImage.js"></script>
</body>
</html>