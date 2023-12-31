<?php

use DECAROLI\controllers\Messages;

require_once "common/head.php";
ini_set('display_errors', "Off");
ini_set('log_errors', "On");
// L'utilisateur qui n'est pas administrateur 
//n'aura pas accées a cette page 
if ($_SESSION['roleUtilisateur'] !== 1) {
    header("Location: /login");
    exit;
}
?>
<link rel="stylesheet" href="../../assets/css/gestion_user.css">
<title>DECAROLI - Tableau de bord</title>
</head>

<body>

    <div id="navbar">
        <ul>
            <li><a href="/" target="_blank">Page promo</a></li>
            <li><a href='/deconnexion'>Se déconnecter</a></li>
        </ul>
    </div>

    <div id="container">
        <form id="form" action="/traitement-ajout-user" method="POST">
            <div id="container-ajout-user">
                <h3>Ajouter un utilisateur</h3>
                <label id="label-nom" for="nom">Nom :</label>
                <input id="input-nom" type="text" name="nom" placeholder="Nom du nouvelle utilisateur">
                <label id="label-mail" for="email">Mail :</label>
                <input id="input-mail" type="email" name="mail" placeholder="Entrez le mail du nouvelle utilisateur">
                <label id="label-mdp" for="mdp">Mot de passe :</label>
                <input id="input-mdp" type="text" name="mdp" placeholder="*********">
                <select name="role">
                    <option value="">Sélectionner un role</option>
                    <?php foreach ($roles as $role) { ?>
                        <option value="<?= $role->getIdRole() ?>"><?= $role->getNomRole() ?></option>
                    <?php }  ?>
                </select>
                <button id="bouton-form" type="submit">Ajouter</button>
            </div>
            <?php $errorMessages = Messages::getMessages();
            if (isset($errorMessages) && !empty($errorMessages)) {
                // Afficher les messages d'erreur
                foreach ($errorMessages as $errorMessage) {
                    echo '<div class="message-alert">' . $errorMessage . '</div>';
                }
            } ?>
        </form>


        <div id="container-liste-utilisateur-et-formulaire-modifier">
            <div id="utilisateur">
                <table>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <!-- Affichage des données de l'utilisateur -->
                            <td><?php echo htmlspecialchars($user->getNom()); ?></td>
                            <td><?php echo htmlspecialchars($user->getMail()); ?></td>
                            <td><?php echo htmlspecialchars($user->getRole()->getNomRole()); ?></td>
                            <td>
                                <!-- Données de l'utilisateur stockés dans des attributs -->
                                <button class="bouton-modifier-utilisateur" data-id="<?= $user->getIdUtilisateur() ?>" data-nom="<?= htmlspecialchars($user->getNom()) ?>" data-mail="<?= htmlspecialchars($user->getMail()) ?>
                                " data-role="<?= $user->getRole()->getIdRole() ?>">Modifier</button>
                                <button class="bouton-supprimer-utilisateur" data-id="<?= $user->getIdUtilisateur() ?>">Supprimer</button>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <form id="form-modification-utilisateur" action="/traitement-modifier-user" method="POST">
                <div id="container-modifier-utilisateur">
                    <label class="label-modifier" for="nom">Nouveau nom :</label>
                    <input id="input-nom-modifier" class="input-modifier" type="text" name="nom">
                    <label class="label-modifier" for="mail">Nouveau mail :</label>
                    <input id="input-mail-modifier" class="input-modifier" type="email" name="mail">
                    <label class="label-modifier" for="mdp">Nouveau mot de passe :</label>
                    <input id="input-mdp-modifier" class="input-modifier" type="text" name="mdp">
                    <select id="select-modifier" name="role">
                        <?php foreach ($roles as $role) { ?>
                            <option value="<?= $role->getIdRole() ?>"><?= $role->getNomRole() ?></option>
                        <?php }  ?>
                    </select>
                    <button id="bouton-form-modification" type="submit">Valider</button>
                </div>
                <input type="hidden" name="idUtilisateur" value="">
            </form>
        </div>
    </div>



    <script src="../../assets/composantJs/gestionUser.js"></script>
</body>

</html>