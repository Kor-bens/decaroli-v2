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
//Fonction qui initialise recaptcha genere un token et envoi le formulaire automatiquement
//avec les données d'identification de l'utilisateur et le token  pour etre traité coté serveur
function initialisationRecaptcha(cleApi,formId) {
    //grecaptcha.ready est utilisé pour s'assurer que l'API reCAPTCHA est chargée et prête
    grecaptcha.ready(function() {
        //grecaptcha.execute est appelé avec la clé API et l'action 'submit'.
        // Cela déclenche la vérification reCAPTCHA et génère un token
        grecaptcha.execute(cleApi, {action: 'submit'}).then(function(token) {
            // Création d'un champ caché qui stockera le token dans le formulaire 
            var form = document.getElementById(formId);
            var champRecaptcha = document.createElement('input');
            //Ajout d'attribut au champ
            champRecaptcha.setAttribute('type', 'hidden');
            champRecaptcha.setAttribute('name', 'g-recaptcha-response');
            champRecaptcha.setAttribute('value', token);
            form.appendChild(champRecaptcha);

            // Envoi du formulaire pour le traitement coté serveur
            form.submit();
        });
    });
}