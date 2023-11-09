<?php
namespace DECAROLI\dao;
class Requete {

        //Requête pour sélectionner le détail d'un utilisateur
    const REQ_USER = "SELECT u.id_utilisateur, u.nom, u.mail, u.mdp, u.id_role, r.nom_role
                            FROM utilisateur u
                            JOIN role r ON u.id_role = r.id_role
                            WHERE nom = :identifiant OR mail = :identifiant";


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

    const REQ_DETAIL_PAGE_PROMOTION = "SELECT p.id_page, p.titre, p.titre_color, p.titre_font_family, p.titre_font_size_grand_ecran, p.titre_font_size_moyen_ecran, p.titre_font_size_petit_ecran, p.bkgd_color
                                     FROM page p  
                                     WHERE p.id_page = 1";

    const REQ_PAGE_IMAGES      = "SELECT nom_image, url, id_image
                                    FROM image
                                    WHERE id_page = 1
                                    ORDER BY id_image ASC";

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

    const REQ_NOM_IMAGE_ID = 'SELECT url 
                                FROM image  
                                  WHERE id_image = :id';
}
