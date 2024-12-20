<?php
require 'vendor/autoload.php';  // Assure-toi que ce chemin est correct

use Dotenv\Dotenv;

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['access_token'])) {
    echo 'Accès non autorisé. Veuillez vous authentifier avec ce lien ';
    echo '<a href="index.php" >Log in </a>';
    exit();
}

$accessToken = $_SESSION['access_token'];

function decodeJWT($jwt) {
    $tokenParts = explode('.', $jwt);
    $tokenPayload = base64_decode($tokenParts[1]);
    return json_decode($tokenPayload, true); // Retourne le payload sous forme de tableau
}

$decodedToken = decodeJWT($accessToken);
$roles = isset($decodedToken['realm_access']['roles']) ? $decodedToken['realm_access']['roles'] : [];
$user_id = $decodedToken['preferred_username'];

if (!in_array('role_user', $roles)) {
    header('Location: hall.php');
    exit();
}

$dbh = new PDO(
    'mysql:host=' . $_ENV['MYSQL_HOST'] . ';dbname=' . $_ENV['MYSQL_DATABASE'] . ';port=' . $_ENV['MYSQL_PORT'] . ';charset=utf8mb4',
    $_ENV['MYSQL_USER'],
    $_ENV['MYSQL_PASSWORD']
);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $dbh->prepare('SELECT * from note WHERE user_id = :user_id');
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
$stmt->execute();

$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$nb_notes = count($notes);
$dbh = null;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateur</title>

    <link rel="stylesheet" href="style.css">
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
        <?php echo htmlspecialchars($decodedToken['preferred_username']); // Affiche le nom de l'utilisateur ?>
    </div>
    <div class="toprightrole">
        Rôles: <?php echo implode(', ', $roles); // Affiche les rôles de l'utilisateur ?>
    </div>
    <div class="toprightlogo">
        <img src="user.png" alt="logo ariovis" height="50">
    </div>
<a href="hall.php" class="button-link">accueil</a>
<a href="./add_note.php" class="button-link">Ajouter une note de frais</a>
<table class="table">
<form action="./delete_note.php" method="post">
    <thead>
        <tr>
            <th class="text-center" colspan="5"><?= $nb_notes?> note de frais</th>
        </tr>
        <tr class="table-info">
            <th>nom</th>
            <th>montant</th>
            <th>valide</th>
	    <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($notes as $note): ?>
            <tr>
                <td><?= htmlspecialchars($note['nom']) ?></td>
                <td><?= htmlspecialchars($note['valeur']) ?></td>
                <td><?= htmlspecialchars($note['statut']) ?></td>
                <td>
		    <input type="hidden" name="id" value="<?= $note["id"] ?>">
                    <input type="hidden" name="nom" value="<?= $note["nom"] ?>">
                    <button type="submit">Supprimer</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</form>
</table>
</body>
</html>
