
        body {background: <?= $donneesOrigine[0]['bkgd_color'] ?>;}
        h1 {background-image: <?= $donneesOrigine[0]['titre_color'] ?>; 
            font-size: <?= $donneesOrigine[0]['titre_font_size_grand_ecran'] ?>;
            font-family: <?= $donneesOrigine[0]['titre_font_family']?>;
            }
            /* Media query pour les grands écrans mobiles (ex. : iPhone 11 Pro Max, Galaxy S21) */
            @media (min-width: 768px) and (max-width:998px) {
            h1{
                font-size:<?= $donneesOrigine[0]['titre_font_size_grand_ecran'] ?>;
                width: 50%;
            }
            }
            /* Media query pour les écrans mobiles de taille moyenne (ex. : iPhone 8, XR) */
            @media (min-width: 321px) and (max-width: 767px) {
                h1{
                    font-size:<?= $donneesOrigine[0]['titre_font_size_moyen_ecran'] ?>;
                }
            }
            /* Media query pour les petits écrans mobiles (ex. : iPhone SE) */
            @media (max-width: 320px) {
            h1{
                font-size:<?= $donneesOrigine[0]['titre_font_size_petit_ecran'] ?>;
            }
            }
    