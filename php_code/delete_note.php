<?php
require 'vendor/autoload.php';  // Assure-toi que ce chemin est correct

use Dotenv\Dotenv;

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

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

try {
    $dbh = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';port=' . $_ENV['DB_PORT'] . ';charset=utf8mb4',
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
$sql = "Delete from note where id=:id";

$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_POST['id']);
if (!empty($_POST['id'])) {
    if ($stmt->execute()) {
        $message = "Réussite de la suppression de la note pour : " . htmlspecialchars($_POST['nom']);
    } else {
        $message = "Erreur lors de la suppression de la note.";
    }
} else {
    $message = "Vous ne supprimez pas de note. ";
}
$dbh = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>supprimer une note - Résultat</title>
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

    <h1 class="titre"><?= $message; ?></h1>
    <a href="./add_note.php" class="button-link">Ajouter une nouvelle note</a><br>
    <a href="./utilisateur.php" class="button-link">Liste des notes</a><br>
    <a href="./hall.php" class="button-link">Accueil</a>
</body>
</html>
