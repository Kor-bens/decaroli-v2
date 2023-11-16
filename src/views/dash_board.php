<?php
use DECAROLI\controllers\Messages;
// session_start();
require_once "common/head.php";

// L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion(retour navigateur)
if (!isset($_SESSION['utilisateur'])) {
    header("Location: /login");
    exit;
}
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=<?= $page->getTitrefontfamily() ?>">
<link rel="stylesheet" href="../../assets/css/admin.css">
<title>DECAROLI - Tableau de bord</title>
</head>
<style>
    #container-representation-page-index {
        background: <?= $page->getBkgdColor() ?>;
    }

    h1 {
        background-image: <?= $page->getTitreColor() ?>;
        color: <?= $page->getTitreColor() ?>;
        font-family: <?= $page->getTitreFontFamily() ?>;
    }
</style>

<body>
    <div id="navbar">
        <ul>
            <li><a href="/" target="_blank">Page promo</a></li>
            <?php
          if (isset($_SESSION['utilisateur'])) {
             //role de l'utilisateur récupéré depuis le controlleur 
              $utilisateurRole = $_SESSION['roleUtilisateur'];
            //si l'utilisateur a le role 1 il aura accèes au gestionnaire d'utilsateur
            if ($utilisateurRole === 1) {
                echo "<li><a href='/gestion-user'>Gestion utilisateur</a></li>";
            }
            echo "<li><a href='/deconnexion'>Se déconnecter</a></li>";
        } else {
            echo 'Les variables de session ont été supprimées.';
        }
            ?>
        </ul>
    </div>


    <div id="container-form">
        <form id="form" action="/traitement-formulaire" method="POST" enctype="multipart/form-data">
            <div id="container-titre">
                <label id="label-titre" for="titre">Titre :</label>
                <input id="input-titre" type="text" name="titre" value="<?= $page->getTitre() ?>">
                <label id="titre-color" for="titre_color">Couleur du titre :</label>
                <input id="input-titre-color" type="text" name="titre_color" value="<?= $page->getTitreColor() ?>">
                <label id="titre_font_family" for="titre_font_family">Police du titre :</label>
                <input id="input-titre-font-family" type="text" name="titre_font_family" value="<?= $page->getTitreFontFamily() ?>">
                <label id="titre_font_size_grand_ecran" for="titre_font_size_grand_ecran">Taille du titre pour version ordinateur :</label>
                <input id="input-titre-font-size_grand_ecran" type="text" name="titre_font_size_grand_ecran" value="<?= $page->getTitreFontSizeGrandEcran() ?>">
                <label id="titre_font_size_grand_ecran" for="titre_font_size_grand_ecran">Taille du titre pour version tablette :</label>
                <input id="input-titre_font_size_moyen_ecran" type="text" name="titre_font_size_moyen_ecran" value="<?= $page->getTitreFontSizeMoyenEcran() ?>">
                <label id="titre_font_size_petit_ecran" for="titre_font_size_petit_ecran">Taille du titre pour version mobile :</label>
                <input id="input-titre_font_size_petit_ecran" type="text" name="titre_font_size_petit_ecran" value="<?= $page->getTitreFontSizePetitEcran() ?>">
            </div>

            <div id="container-image">
                    <label for="image" id="label-ajouter-image">Ajouter des images</label>
                    <button type="button" id="bouton-ajouter-image">Sélectionner des images</button>
                    <?php
                        $errorMessages = Messages::getMessages();
                        if (isset($errorMessages) && !empty($errorMessages)) {
                            // Afficher les messages d'erreur
                            foreach ($errorMessages as $errorMessage) {
                                echo '<div class="message-alert">' . $errorMessage . '</div>';
                            }
                    }?>
                    <input id="input-ajouter-image" type="file" name="image" accept="image/*" style="display: none;" multiple>
                <!-- Ajoutez un élément img pour afficher l'image sélectionnée -->
                <img id="image-selectionnee" src="" alt="Image sélectionnée" style="display: none;">
            </div>

            <div id="container-background">
                <label for="background">Arrière plan de la page:</label>
                <input type="text" name="background" value="<?= $page->getBkgdColor() ?>"><br>
            </div>
            <button id="bouton-form" type="submit">Valider</button>
        </form>

        <div id="container-representation-page-index">
            <h1><?= $page->getTitre() ?></h1>
            <div id="container-image-db">
                <?php
                // Initialisez une variable pour suivre la position de l'image
                $position = 0;
                foreach ($images as $image) :
                    // Incrémentez la position à chaque itération
                    $position++;
                    // Modulo pour affecter flex-start ou flex-end (pair ou impair) s'il y a un reste ou pas 
                    $class = ($position % 2 === 0) ? 'flex-start' : 'flex-end';
                  ?>
                    <div class="image <?= $class ?>">
                        <img src="../../assets/ressources/images/<?= $image->getUrl() ?>" alt="<?= $image->getNomImage() ?>">
                        <div class="container-button">
                            <button class="bouton-modifier-image" data-id="<?= $image->getIdImage() ?>">Modifier</button>
                            <!-- Champ de téléchargement de fichier pour la modification de l'image -->
                            <input type="file" class="champ-modifier-image input-image" data-id="<?= $image->getIdImage() ?>">
                            <!-- Bouton "Modifier" avec l'attribut onclick -->
                            <button class="bouton-supprimer-image" data-id="<?= $image->getIdImage() ?>">Supprimer</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
   
    <script src="../../assets/composantJs/admin.js"></script>
    <script src="../../assets/composantJs/supprimerModifierImage.js"></script>
</body>

</html>