document.addEventListener("DOMContentLoaded", function () {
    //Séléction de tout les boutons pour supprimer
    const boutons = document.querySelectorAll(".bouton-supprimer-utilisateur");
    //Executiuon de la fonction pour chaque bouton 
    boutons.forEach(function (bouton) {
        //Au click on récupère l'id du bouton de l'utilisateur
        bouton.addEventListener("click", function () {
            const idUser = bouton.getAttribute("data-id");
            // Requete Ajax de l'url avec en paramètre l'id de l'utilisateur et la methode http
            fetch(`/traitement-supprimer-user?idUser=${idUser}`, {
                //Methode pour supprimer
                method: "DELETE",
            })
            //Si la suppression a fonctionné la page est rechargé
            .then(function (response) {
                if (response.ok) {
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


document.addEventListener("DOMContentLoaded", function () {
    //Séléction des éléments du formulaire
    let boutonsModifier = document.querySelectorAll(".bouton-modifier-utilisateur");
    let formModifier = document.querySelector("#form-modification-utilisateur");
    let inputNom = document.querySelector("#input-nom-modifier");
    let inputMail = document.querySelector("#input-mail-modifier");
    let inputMdp = document.querySelector("#input-mdp-modifier");
    let selectRole = document.querySelector("select[name='role']");
    
    boutonsModifier.forEach(function (bouton) {
        bouton.addEventListener("click", function() {
            const idUser = bouton.getAttribute("data-id");
            const nomUser = bouton.getAttribute("data-nom");
            const mailUser = bouton.getAttribute("data-mail");
            const roleId = bouton.getAttribute("data-role");

            console.log(idUser); 
            console.log(nomUser); 
            console.log(roleId); 
            console.log(mailUser); 

            inputNom.value = nomUser;
            inputMail.value = mailUser;
            selectRole.value = roleId;

            formModifier.style.display = "flex";

            // Vous pouvez ajouter un champ caché pour l'ID de l'utilisateur
            formModifier.querySelector("input[name='idUtilisateur']").value = idUser;
        })
    })
})