<?php

class Requete {

    // Requête pour sélectionner le nom d'un administrateur par nom
    const REQ_NOM_ADMIN = "SELECT id_admin, nom, mdp 
                            FROM administrateur
                            WHERE nom = :nom";

    const REQ_NOM_MAIL_ADMIN = "SELECT id_admin, nom, mail 
    FROM administrateur
    WHERE nom = :nom OR mail = :mail";

    // Requête pour modifier le titre d'une page par ID de page
    const REQ_MODIF_TITRE = "UPDATE page 
                             SET titre = :titre 
                             WHERE id_page = 1";

    // Requête pour modifier les paramètres de style de la page par ID de page
    const REQ_MODIF_BACKGROUND = "UPDATE page
                                 SET bkgd_color = :background, 
                                     titre = :titre, 
                                     titre_color = :titre_color, 
                                     titre_font_family = :titre_font_family, 
                                     titre_font_size_grand_ecran = :titre_font_size_grand_ecran, 
                                     titre_font_size_moyen_ecran = :titre_font_size_moyen_ecran, 
                                     titre_font_size_petit_ecran = :titre_font_size_petit_ecran 
                                 WHERE id_page = 1";

    // Requête pour récupérer le titre, les couleurs et les images associées à une page
    const REQ_TITRE_COULEUR_IMAGE = "SELECT p.titre, p.titre_color, p.titre_font_family, p.titre_font_size_grand_ecran, p.titre_font_size_moyen_ecran, p.titre_font_size_petit_ecran, p.bkgd_color, i.nom_image, i.url, i.id_image
                                     FROM page p
                                     LEFT JOIN image i ON p.id_page = i.id_page
                                     WHERE p.id_page = 1";

    // Requête pour ajouter une image avec son URL et son nom associé à une page
    const REQ_AJOUT_IMAGE = "INSERT INTO image (url, nom_image, id_page)
                             VALUES (:url, :nom_image, :id_page)";

    // Requête pour modifier le nom et l'URL d'une image par ID d'image
    const REQ_MODIF_IMAGE = "UPDATE image
                             SET nom_image = :nom_image, url = :url 
                             WHERE id_image = :id_image";

    // Requête pour obtenir l'ID, l'URL et le nom d'une image associée à une page
    const REQ_ID_URL_NOM_IMAGE = "SELECT id_image, nom_image, url 
                                FROM image 
                                WHERE id_page = 1";

    // Requête pour supprimer une image par ID d'image
    const REQ_SUPPR_IMAGE = "DELETE FROM image
                             WHERE id_image = :idImage";
}
