let boutonMdpOublie = document.querySelector('#mdp-oublie');
let formResetMdp    = document.querySelector('#resetPassword');
let loginForm       = document.querySelector("#loginForm");
let seConnecter     = document.querySelector("#se-connecter");

boutonMdpOublie.addEventListener('click', () =>{
    formResetMdp.style.display = 'flex';
    formResetMdp.style.flexDirection = 'column';
    loginForm.style.display = "none";
})

seConnecter.addEventListener('click',() =>{
    formResetMdp.style.display = 'none';
    loginForm.style.display = "flex";
})


function initiateRecaptcha(resetPassword) {
    grecaptcha.ready(function() {
        grecaptcha.execute('6Lfkds8oAAAAAPRPPv2yTGlZAECdMaHA-jMqnkon', {action: 'submit'}).then(function(token) {
            // Ajouter le token à un champ caché
            var form = document.getElementById(resetPassword);
            var champRecaptcha = document.createElement('input');
            champRecaptcha.setAttribute('type', 'hidden');
            champRecaptcha.setAttribute('name', 'g-recaptcha-response');
            champRecaptcha.setAttribute('value', token);
            form.appendChild(champRecaptcha);

            // Soumettez le formulaire
            form.submit();
        });
    });
}
//Fonction appeler au click du formulaire qui va charger la bibliothèque recaptcha
function initiateRecaptcha(cleApi,formId) {
    grecaptcha.ready(function() {
        alert('coucou');
        //execution de la methode qui identifie la clé et contextualise la verification du recaptcha (soumission de formulaire)
        grecaptcha.execute(cleApi, {action: 'submit'}).then(function(token) {
            // Ajouter le token a un champ caché dans le formulaire
            var form = document.getElementById(formId);
            var champRecaptcha = document.createElement('input');
            champRecaptcha.setAttribute('type', 'hidden');
            champRecaptcha.setAttribute('name', 'g-recaptcha-response');
            champRecaptcha.setAttribute('value', token);
            form.appendChild(champRecaptcha);

            // Soumission du formulaire, 
            form.submit();
        });
    });
}