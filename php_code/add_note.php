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
$roles = isset($decodedToken['realm_access']['roles']) ? $decodedToken['realm_access']['roles'] : [];
$user_id = $decodedArray['preferred_username'];

if (!in_array('role_user', $roles)) {
    header('Location: hall.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de note</title>
    <link rel="stylesheet" href="../nginx/html/style.css">

</head>

<body style="background-color:#1E293B;">

    <div class="top">
        <header>
            <div class="logotopleft">
                <img src="logo.png" alt="logo ariovis" width="50" height="50">
            </div>
            <div class="placetopleft">
                <h1>Utilisateur</h1>
            </div>
        </header>
    </div>
    <div class="toprightuser">
        <?php echo htmlspecialchars($decodedToken['preferred_username']); ?>
    </div>
    <div class="toprightrole">
        Rôles: <?php echo implode(', ', $roles); ?>
    </div>
    <div class="toprightlogo">
        <img src="user.png" alt="logo ariovis" height="50">
    </div>

    <div class="centrage">
        <center>
            <div class="wrapper">
                <h2>Ajouter une note</h2>

                <form action="process_note.php" method="POST">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($decodedToken['preferred_username']) ?>">

                    <!-- Nom -->
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" required><br><br>

                    <!-- Valeur (nombre avec 2 décimales) -->
                    <label for="valeur">Montant de la note :</label>
                    <input type="number" id="valeur" name="valeur" step="0.01" min="0" max="999.99" required><br><br>

                    <!-- Statut par défaut à 'en cours' (invisible) -->
                    <input type="hidden" name="statut" value="en cours">

                    <!-- Bouton d'envoi -->
                    <input type="submit" value="Ajouter la note">
                </form>
            </div>
        </center>
    </div>

    <div class="footer">
        <a href="utilisateur.php" class="button-link">RETOUR</a>
    </div>

</body>
</html>
