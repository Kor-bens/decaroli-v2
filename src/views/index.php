<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez les meilleures offres du mois sur les produits pharmaceutiques de Decaroli.
                                     Profitez de remises exclusives sur une large gamme d'articles, des soins pour bébés aux produits minceur. 
                                     Économisez sur des essentiels de santé et de bien-être. Ne manquez pas nos offres exceptionnelles ce mois-ci !" />
    <title>DECAROLI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=<?= $donneesOrigine[0]['titre_font_family']?>">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
 <style>
        body {background: <?= $donneesOrigine[0]['bkgd_color'] ?>;}
        h1 {background-image: <?= $donneesOrigine[0]['titre_color'] ?>; 
            font-size: <?= $donneesOrigine[0]['titre_font_size'] ?>;
            font-family: <?= $donneesOrigine[0]['titre_font_family']?>;
            }
    </style>  
<body>
<h1><?= $donneesOrigine[0]['titre'] ?></h1>
     <?php echo $donneesOrigine[0]['titre_font_family'];
    var_dump($donneesOrigine);?>

    <div id="contain-images">
    <?php
    // Fonction de comparaison pour trier le tableau par ID croissant
    function compareById($a, $b) {
        return $a['id_image'] - $b['id_image'];
    }
    // Tri du tableau $donneesOrigine par ID croissant
    usort($donneesOrigine, 'compareById');?>
    <?php foreach ($donneesOrigine as $image) : ?>
        <div class="image"><img src="../../assets/ressources/images/<?=$image['url'] ?>" alt="<?= $image['nom_image'] ?>"></div>
    <?php endforeach; ?>
    </div>

    <div id="div-fleche"></div>

    <div id="footer">
        <img id="logo-decaroli" src="../../assets/ressources/images/logoDecaroli.png" alt="logoDecaroli">
    </div>
    
    
    <script src="../../assets/composantJs/app.js"></script>
</body>
</html>