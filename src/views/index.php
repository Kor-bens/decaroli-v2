<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DECAROLI</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
 <style>
        body {background: <?= $donneesOrigine[0]['bkgd_color'] ?>;}
        h1 {background: <?= $donneesOrigine[0]['bkgd_color'] ?>;}
    </style>  
<body>
<h1><?= $donneesOrigine[0]['titre'] ?></h1>
     <!-- <?php echo $donneesOrigine[0]['titre'];
    var_dump($donneesOrigine);?> -->

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

    <div id="div-fleche">
      
        </div>

    <div id="footer">
        <img id="logo-decaroli" src="../../assets/ressources/images/logoDecaroli.png" alt="logoDecaroli">
    </div>
    
    
    <script src="../../assets/composantJs/app.js"></script>
</body>
</html>