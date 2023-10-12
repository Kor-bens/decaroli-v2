document.addEventListener("DOMContentLoaded", function () {
    const containerImageDb = document.getElementById("container-image-db");

    // Appliquez les styles CSS au conteneur d'images
    containerImageDb.style.display = "grid";
    containerImageDb.style.gridTemplateColumns = "repeat(2, 1fr)";
    containerImageDb.style.width = "100%";
    containerImageDb.style.marginBottom = "2%";
    containerImageDb.style.marginTop = "2%";


    const images = document.querySelectorAll(".image");
    // Parcourez chaque image et attribuez une classe en fonction de la position
    images.forEach((image, index) => {
        if (index % 2 === 0) {
            // image.style.display = "flex";
            image.classList.add("flex-end"); 
        } else {
            // image.style.display = "flex";
            image.classList.add("flex-start"); 
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const labelAjouterImage = document.getElementById("label-ajouter-image");
    const boutonAjouterImage = document.getElementById("bouton-ajouter-image");
    const inputAjouterImage = document.getElementById("input-ajouter-image");
    const imageSelectionnee = document.getElementById("image-selectionnee");
    
    if (labelAjouterImage && boutonAjouterImage && inputAjouterImage && imageSelectionnee) {
        // Lorsque le bouton est cliqué, déclenchez le champ de fichier
        boutonAjouterImage.addEventListener("click", function () {
            inputAjouterImage.click();
        });
        
        // Lorsque l'utilisateur a sélectionné un fichier, lisez-le et affichez-le
        inputAjouterImage.addEventListener("change", function () {
            const files = inputAjouterImage.files;
            if (files.length > 0) {
                // Lisez le fichier en tant qu'objet Blob
                const blob = URL.createObjectURL(files[0]);
                
                // Mettez à jour l'élément img avec l'URL de l'image
                imageSelectionnee.src = blob;
                imageSelectionnee.style.display = "flex"; // Affichez l'image
                imageSelectionnee.style.margin= "0 auto";
            } else {
                // Cachez l'image si aucun fichier n'est sélectionné
                imageSelectionnee.src = "";
                imageSelectionnee.style.display = "none";
            }
        });
    }
});
  