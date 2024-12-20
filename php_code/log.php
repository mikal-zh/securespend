<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['access_token'])) {
    echo 'Accès non autorisé. Veuillez vous authentifier. ';
    echo '<a href="index.php">Connexion</a>';
    exit();
}

// Affichez le message pour l'utilisateur connecté
$user = $_SESSION['user']->name ?? 'Utilisateur';

echo "Hello World, " . htmlspecialchars($user) . "!";
?>
