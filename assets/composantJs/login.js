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