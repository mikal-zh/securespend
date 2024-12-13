<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    echo 'Accès non autorisé. Veuillez vous authentifier ';
    echo '<a href="index.php" >Log in </a>';
    exit();
}

$accessToken = $_SESSION['access_token'];

function decodeJWT($jwt) {
    $tokenParts = explode('.', $jwt);
    $tokenPayload = base64_decode($tokenParts[1]);
    return json_decode($tokenPayload, true);
}

$decodedToken = decodeJWT($accessToken);
$user = isset($decodedToken['preferred_username']) ? $decodedToken['preferred_username'] : 'Utilisateur';
$roles = isset($decodedToken['realm_access']['roles']) ? $decodedToken['realm_access']['roles'] : [];

$canSaisir = in_array('role_user', $roles);
$canValider = in_array('role_admin', $roles);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color:#0F172A;">

    <div class="top">
        <header>
            <div class="toprightlogo">
                <img src="user.png" alt="logo ariovis" height="50">
            </div>
            <div class="logotopleft">
                <img src="logo.png" alt="logo ariovis" width="50" height="50">
            </div>
            <div class="placetopleft">
                <h1>accueil</h1>
            </div>
        </header>
    </div>
    <div class="toprightuser">
        <?php echo htmlspecialchars($user); // Affiche le nom de l'utilisateur ?>
    </div>
    <div class="toprightrole">
        Rôles: <?php echo implode(', ', $roles); // Affiche les rôles de l'utilisateur ?>
    </div>

    <div class="centrage">
        <center>
            <?php
            if ($canSaisir) {
                echo '<a href="utilisateur.php" class="button-link">SAISIR</a>';
            } else {
                echo '<p>Vous n\'avez pas accès à SAISIR.</p>';
            }
            echo '<br>';
            if ($canValider) {
                echo '<a href="management.php" class="button-link">VALIDER</a>';
            } else {
                echo '<p>Vous n\'avez pas accès à VALIDER.</p>';
            }
            ?>
        </center>
    </div>

</body>
</html>
