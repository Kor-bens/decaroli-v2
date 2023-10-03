document.addEventListener("DOMContentLoaded", function () {
    const boutons = document.querySelectorAll(".bouton-supprimer-image, .bouton-modifier-image");

    boutons.forEach(function (bouton) {
        bouton.addEventListener("click", function () {
            const idImage = bouton.getAttribute("data-id");

            // Déterminez si le bouton cliqué est un bouton "Supprimer" ou "Modifier"
            const action = bouton.classList.contains("bouton-supprimer-image") ? "supprimer" : "modifier";
            console.log(action, idImage);
            if (action === "modifier") {
                // Déclencher le champ de téléchargement de fichier associé
                const champModifierImage = document.querySelector(`.champ-modifier-image[data-id="${idImage}"]`);
                champModifierImage.click();
            } else {
                // Envoyez une requête AJAX pour supprimer l'image
                fetch(`/supprimer-image?idImage=${idImage}`, {
                    method: "DELETE",
                })
                .then(function (response) {
                    if (response.ok) {
                        // L'image a été supprimée avec succès, alerte l'utilisateur
                        alert("Image supprimée");
                        // Mettez à jour l'affichage
                        bouton.parentElement.remove();
                    } else {
                        console.error("Erreur lors de la suppression de l'image.");
                    }
                })
                .catch(function (error) {
                    console.error("Erreur lors de la suppression de l'image : " + error);
                });
            }
        });
    });
});

// Gestion de l'envoi du nouveau fichier image lors de la modification
document.addEventListener("change", function (event) {
    const inputImage = event.target;
    if (inputImage.classList.contains("champ-modifier-image")) {
        const idImage = inputImage.getAttribute("data-id");
        const fichierImage = inputImage.files[0];

        if (fichierImage) {
            const formData = new FormData();
            formData.append("idImageModifier", idImage); // ID de l'image à modifier
            formData.append("nouveau_nom_image", fichierImage.name); // Nom du fichier sélectionné
            formData.append("nouvelle_url", generateUniqueURL(fichierImage.name)); // Générez une URL unique
            formData.append("nouvelleImage", fichierImage);

            // Envoyez une requête AJAX pour modifier l'image
            fetch(`/modifier-image`, {
                method: "POST", // Utilisez la méthode POST pour envoyer le fichier
                body: formData,
            })
            .then(function (response) {
                if (response.ok) {
                    // L'image a été modifiée avec succès, alerte l'utilisateur
                    alert("Image modifiée");
                    // Mettez à jour l'affichage si nécessaire
                } else {
                    console.error("Erreur lors de la modification de l'image.");
                }
            })
            .catch(function (error) {
                console.error("Erreur lors de la modification de l'image : " + error);
            });
        }
    }
});

// Fonction pour générer une URL unique basée sur le nom du fichier
function generateUniqueURL(fileName) {
    const timestamp = new Date().getTime(); // Obtenez un horodatage unique
    const uniqueURL = `${fileName}_${timestamp}`;
    return uniqueURL;
}