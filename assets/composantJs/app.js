document.addEventListener("DOMContentLoaded", function () {
   
           // Sélectionnez toutes les images 
    const images = document.querySelectorAll(".image");

    // Fonction pour faire apparaître une image avec une animation
    function fadeInImage(image) {
        setTimeout(function () {
            image.style.display = "flex";
            image.classList.add("circular");
        }, 1000); // Ajustez la valeur 1000 (en millisecondes) pour ajuster le délai d'apparition
    }
    

    // Faites apparaître chaque image avec un délai
    images.forEach(function (image) {
        fadeInImage(image);
    });
});
/*selectionne l'id*/
let divFleche = document.querySelector("#div-fleche");

/*creation d'une div*/ 
let cercle = document.createElement("div");
cercle.id="cercle";
divFleche.appendChild(cercle);

/*ajout d'interaction defilement vers le haut sur le cercle au click*/
cercle.addEventListener("click", scrollTop);

/*creation d'une image qui represente la fleche*/
let fleche = document.createElement("img");
fleche.src = "../../assets/ressources/images/fleche.png";
fleche.id ="fleche";
cercle.appendChild(fleche);

/*ajout d'interaction defilement vers le haut sur la flèche au click*/
fleche.addEventListener("click", scrollTop);

/*ecouteur d'evenement sur le scroll de la page*/ 
window.addEventListener('scroll', toggleScrollTop);
console.log(window.scrollY);
// Fonction pour faire défiler vers le haut de la page
function scrollTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

function toggleScrollTop() {
    // Ne pas activer sur les écrans de largeur <= 768 pixels
    if (window.innerWidth > 768) { 
        if (window.scrollY > 600) {
            cercle.classList.add('active');
            cercle.style.display = "flex";
            cercle.style.alignItems = "center";
            cercle.style.justifyContent = "center";
            console.log("cercle visible");
        } else {
            cercle.style.display = "none";
            console.log("cercle disparait");
        }

        if (window.scrollY > 600) {
            fleche.classList.add('active');
            fleche.style.display = "block";
            console.log("fleche visible");
        } else {
            fleche.style.display = "none";
            console.log("fleche disparait");
        }
    }
}



window.addEventListener('scroll', function() {
    console.log(window.scrollY);
});