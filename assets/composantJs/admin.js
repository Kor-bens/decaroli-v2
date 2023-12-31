
document.addEventListener("DOMContentLoaded", function () {
    const labelAjouterImage = document.getElementById("label-ajouter-image");
    const boutonAjouterImage = document.getElementById("bouton-ajouter-image");
    const inputAjouterImage = document.getElementById("input-ajouter-image");
    const imageSelectionnee = document.getElementById("image-selectionnee");
    
    if (labelAjouterImage && boutonAjouterImage && inputAjouterImage && imageSelectionnee) {
        // Lorsque le bouton est cliqué, déclenche le champ de fichier
        boutonAjouterImage.addEventListener("click", function () {
            inputAjouterImage.click();
        });
        
        // Lorsque l'utilisateur a sélectionné un fichier, lis et affiche le fichier
        inputAjouterImage.addEventListener("change", function () {
            const files = inputAjouterImage.files;
            if (files.length > 0) {
                // Lis le fichier en tant qu'objet Blob
                const blob = URL.createObjectURL(files[0]);
                
                // Met à jour l'élément img avec l'URL de l'image
                imageSelectionnee.src = blob;
                imageSelectionnee.style.display = "flex"; 
                imageSelectionnee.style.margin= "0 auto";
            } else {
                // Cache l'image si aucun fichier n'est sélectionné
                imageSelectionnee.src = "";
                imageSelectionnee.style.display = "none";
            }
        });
    }
});

