<?php

require_once "common/head.php";?>

    <meta name="description" content="Découvrez les meilleures offres du mois sur les produits pharmaceutiques de Decaroli.
                                     Profitez de remises exclusives sur une large gamme d'articles, des soins pour bébés aux produits minceur. 
                                     Économisez sur des essentiels de santé et de bien-être. Ne manquez pas nos offres exceptionnelles ce mois-ci !" />
    <title>DECAROLI</title>
    <link rel="icon" type="image/png" href="../../assets/ressources/images/logoDecaroli.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=<?= $page->getTitreFontFamily() ?>">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<style>
    body {
        background: <?= $page->getBkgdColor() ?>;
    }
    /*Titre promotions*/
    h1 {
        background-image: <?= $page->getTitreColor() ?>;
        color: <?= $page->getTitreColor() ?>;
        font-size: <?= $page->getTitreFontSizeGrandEcran() ?>;
        font-family: <?= $page->getTitrefontfamily() ?>;
    }
    /* Media query pour les grands écrans */
    @media (min-width: 768px) {
        h1 {
            font-size: <?= $page->getTitreFontSizeGrandEcran() ?>;
        }
    }
    /* Media query pour les écrans de taille moyenne  */
    @media (min-width: 431px) and (max-width: 768px) {
        h1 {
            font-size: <?= $page->getTitreFontSizeMoyenEcran() ?>;
        }
    }
    /* Media query pour les petits écrans  */
    @media (max-width: 431px) {
        h1 {
            font-size: <?= $page->getTitreFontSizePetitEcran() ?>;
        }
    }
</style>

<body>
      <!-- Afficher le titre grace a la methode de l'objet $page getTitre() qui récupere le titre en base de donnée   -->
    <h1><?= $page->getTitre() ?></h1>
  
     <!-- bloc qui contient les images de la base de donnée -->
    <div id="contain-images">
                <?php
                // Initialise une variable pour suivre la position de l'image
                $position = 0;
                
                foreach ($images as $image) :
                    // Incrémente la position à chaque itération d'image
                    $position++;

                    // Modulo pour affecter flex-start ou flex-end (pair ou impair) s'il y a un reste ou pas 
                    $class = ($position % 2 === 0) ? 'flex-start' : 'flex-end';
                ?>

                <div class="image <?= $class ?>">
                    <?php $nom_image_sans_extension = pathinfo($image->getNomImage(), PATHINFO_FILENAME); ?>
                    <img src="../../assets/ressources/images/<?= $image->getUrl() ?>" alt="<?= $nom_image_sans_extension ?>">
                </div>
             <?php endforeach; ?>
    </div>

    <div id="div-fleche" alt="fleche"></div>

    <div id="footer">
        <img id="logo-decaroli" src="../../assets/ressources/images/logoDecaroli.png" alt="logoDecaroli">
    </div>


    <script src="../../assets/composantJs/app.js"></script>
</body>

</html>