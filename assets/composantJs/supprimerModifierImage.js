document.addEventListener("DOMContentLoaded", function () {
    const boutons = document.querySelectorAll(".bouton-supprimer-image");

    boutons.forEach(function (bouton) {
        bouton.addEventListener("click", function () {
            const idImage = bouton.getAttribute("data-id");

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
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const boutonsModifier = document.querySelectorAll(".bouton-modifier-image");

    boutonsModifier.forEach(function (bouton) {
        bouton.addEventListener("click", function () {
            const idImage = bouton.getAttribute("data-id");
            const champModifierImage = document.querySelector(`.champ-modifier-image[data-id="${idImage}"]`);
            console.log("id de l'image ", idImage );
            // Lorsque l'utilisateur clique sur le bouton "Modifier", ouvrez la boîte de dialogue de sélection de fichier
            champModifierImage.click();

            // Gérez le changement de fichier sélectionné
            champModifierImage.addEventListener("change", function () {
                const fichierImage = champModifierImage.files[0];
                if (fichierImage) {
                    modifierImage(idImage, fichierImage);
                }
            });
        });
    });
    
    // Fonction pour envoyer le fichier image pour modification via une requête AJAX
    function modifierImage(idImage, fichierImage) {
        const formData = new FormData();
        formData.append("idImageModifier", idImage); // ID de l'image à modifier
        formData.append("nouveau_nom_image", fichierImage.name); // Nom du fichier sélectionné
        formData.append("nouvelleImage", fichierImage);
        console.log(fichierImage);

        fetch("/modifier-image", {
            method: "POST", // Utilisez la méthode POST pour envoyer le fichier
            body: formData,
        })
        .then(function (response) {
            if (response.ok) {
                // L'image a été modifiée avec succès, alertez l'utilisateur ou effectuez d'autres actions
                alert("Image modifiée avec succès !");
            } else {
                console.error("Erreur lors de la modification de l'image.");
            }
        })
        .catch(function (error) {
            console.error("Erreur lors de la modification de l'image : " + error);
        });
    }
});