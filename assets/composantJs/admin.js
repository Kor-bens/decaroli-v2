let images          = document.querySelectorAll("img");
let boutonSupprimer = document.querySelector(".bouton-supprimer-image");
let boutonModifier  = document.querySelector(".bouton-modifier-image");
boutonModifier.style.display="none";
boutonSupprimer.style.display="none";

images.forEach((img) => {
    boutonSupprimer.style.display ="flex";
    boutonModifier.style.display  ="flex";
   img.appendChild(boutonSupprimer);
   img.appendChild(boutonModifier);
});


document.addEventListener("DOMContentLoaded", function () {
    // Sélectionnez toutes les images par leur classe CSS
    const images = document.querySelectorAll(".image");
    // images.style.display = "flex";

  
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

function chooseFile() {
    document.getElementById('image').click(); // Cliquez sur l'élément file input
}

document.getElementById('image').addEventListener('change', function() {
    const fileInput = document.getElementById('image');
    const customFileLabel = document.getElementById('custom-file-label');
    
    if (fileInput.files.length > 0) {
        customFileLabel.textContent = fileInput.files[0].name;
    } else {
        customFileLabel.textContent = 'Aucun fichier sélectionné';
    }
});



  