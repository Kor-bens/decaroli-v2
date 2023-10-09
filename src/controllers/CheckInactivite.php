<?php
class CheckInactivite {
    public function checkUserInactivity() {
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            // Durée d'inactivité maximale (en secondes)
            $inactiveTimeout = 5; // 5 minutes

            if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactiveTimeout)) {
                // L'utilisateur est inactif depuis plus de 5 minutes, déconnectez-le
                session_unset();
                session_destroy();
                header("Location: /login");
                exit();
            } else {
                // Mettre à jour l'horodatage de la dernière activité
                $_SESSION['last_activity'] = time();
            }
        }
    }
}
?>