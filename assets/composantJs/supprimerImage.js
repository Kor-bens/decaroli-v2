document.addEventListener("DOMContentLoaded", function () {
    const boutonsSupprimer = document.querySelectorAll(".bouton-supprimer-image");

    boutonsSupprimer.forEach(function (bouton) {
        bouton.addEventListener("click", function () {
            const idImage = bouton.getAttribute("data-id");

            // Envoyez une requête AJAX pour supprimer l'image avec l'ID idImage
            fetch(`/supprimer-image?idImage=${idImage}`, {
                method: "DELETE",
            })
            .then(function (response) {
                if (response.ok) {
                    // L'image a été supprimée avec succès, alerte l'utilisateur
                    alert("Image supprimée");
                    // Mettez à jour l'affichage
                    bouton.parentElement.remove();
                    // Rechargez la page admin pour afficher les changements
                    location.reload();
                } else {
                    console.error("Erreur lors de la suppression de l'image.");
                }
            })
            .catch(function (error) {
                console.error("Erreur lors de la suppression de l'image : " + error);
            });
        });
    });
});