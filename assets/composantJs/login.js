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


// function initiateRecaptcha(resetPassword) {
//     grecaptcha.ready(function() {
//         grecaptcha.execute('6Lfkds8oAAAAAPRPPv2yTGlZAECdMaHA-jMqnkon', {action: 'submit'}).then(function(token) {
//             // Ajouter le token à un champ caché
//             var form = document.getElementById(resetPassword);
//             var hiddenField = document.createElement('input');
//             hiddenField.setAttribute('type', 'hidden');
//             hiddenField.setAttribute('name', 'g-recaptcha-response');
//             hiddenField.setAttribute('value', token);
//             form.appendChild(hiddenField);

//             // Soumettez le formulaire
//             form.submit();
//         });
//     });
// }

// function initiateRecaptcha(formId) {
//     grecaptcha.ready(function() {
//         grecaptcha.execute('6Lfkds8oAAAAAPRPPv2yTGlZAECdMaHA-jMqnkon', {action: 'submit'}).then(function(token) {
//             // Ajouter le token à un champ caché
//             var form = document.getElementById(formId);
//             var hiddenField = document.createElement('input');
//             hiddenField.setAttribute('type', 'hidden');
//             hiddenField.setAttribute('name', 'g-recaptcha-response');
//             hiddenField.setAttribute('value', token);
//             form.appendChild(hiddenField);

//             // Soumettez le formulaire
//             form.submit();
//         });
//     });
// }