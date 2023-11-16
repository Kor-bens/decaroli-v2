document.addEventListener("DOMContentLoaded", function () {
     // Sélectionnez toutes les images 
    const images = document.querySelectorAll(".image");

    // Fonction pour ajouter une class pour animer l'image et l'appliquer a partir d'un délai 
    function StartAnimationCirculaire(image) {
        setTimeout(function () {
            image.classList.add("circulaire");
        }, 1000); 
    }
    
    //  Applique StartAnimationCirculaire(image) pour chaque itération d'image
    images.forEach(function (image) {
        StartAnimationCirculaire(image);
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
fleche.setAttribute('alt', 'fleche');
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

