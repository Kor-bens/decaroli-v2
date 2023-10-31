<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez les meilleures offres du mois sur les produits pharmaceutiques de Decaroli.
                                     Profitez de remises exclusives sur une large gamme d'articles, des soins pour bébés aux produits minceur. 
                                     Économisez sur des essentiels de santé et de bien-être. Ne manquez pas nos offres exceptionnelles ce mois-ci !" />
    <title>DECAROLI</title>
    <link rel="icon" type="image/png"  href="../../assets/ressources/images/logoDecaroli.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=<?= $DetailPage[0]['titre_font_family']?>">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
 <style>
        body {background: <?= $DetailPage[0]['bkgd_color'] ?>;}
        h1 {background-image: <?= $DetailPage[0]['titre_color'] ?>; 
            color           : <?= $DetailPage[0]['titre_color'] ?>;
            font-size: <?= $DetailPage[0]['titre_font_size_grand_ecran'] ?>;
            font-family: <?= $DetailPage[0]['titre_font_family']?>;
            }
            /* Media query pour les grands écrans mobiles (ex. : iPhone 11 Pro Max, Galaxy S21) */
            @media (min-width: 768px)  {
            h1{
                font-size:<?= $DetailPage[0]['titre_font_size_grand_ecran'] ?>;
                /* width: 50%; */
            }
            }
            /* Media query pour les écrans mobiles de taille moyenne (ex. : iPhone 8, XR) */
            @media (min-width: 431px) and (max-width: 768px) {
                h1{
                    font-size:<?= $DetailPage[0]['titre_font_size_moyen_ecran'] ?>;
                }
            }
            /* Media query pour les petits écrans mobiles (ex. : iPhone SE) */
            @media (max-width: 431px) {
            h1{
                font-size:<?= $DetailPage[0]['titre_font_size_petit_ecran'] ?>;
            }
            }
    </style>  
<body>
<h1><?= $DetailPage[0]['titre'] ?></h1>
     <!-- <?php echo $DetailPage[0]['titre_font_family'];
    var_dump($DetailPage);?> -->

<div id="contain-images">
    <?php
    // Fonction de comparaison pour trier le tableau par ID croissant
    function compareById($a, $b) {
        return $a['id_image'] - $b['id_image'];
    }

    // Tri du tableau $donneesOrigine par ID croissant
    usort($DetailPage, 'compareById');

    // Initialisez une variable pour suivre la position de l'image
    $position = 0;

    foreach ($DetailPage as $image) :
        // Incrémentez la position à chaque itération
        $position++;

        // Appliquez la classe CSS en fonction de la position
        $class = ($position % 2 === 0) ? 'flex-start' : 'flex-end';
    ?>
        <div class="image <?= $class ?>">
        <?php $nom_image_sans_extension = pathinfo($image['nom_image'], PATHINFO_FILENAME); ?>
            <img src="../../assets/ressources/images/<?= $image['url'] ?>" alt="<?=$nom_image_sans_extension ?>">
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