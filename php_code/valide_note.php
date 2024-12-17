<?php
require 'vendor/autoload.php';  // Assure-toi que ce chemin est correct

use Dotenv\Dotenv;

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

if (!in_array('role_user', $roles)) {
    header('Location: hall.php');
    exit();
}

try {
    $dbh = new PDO(
        'mysql:host=' . $_ENV['MYSQL_HOST'] . ';dbname=' . $_ENV['MYSQL_DATABASE'] . ';port=' . $_ENV['MYSQL_PORT'] . ';charset=utf8mb4',
        $_ENV['MYSQL_USER'],
        $_ENV['MYSQL_PASSWORD']
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "UPDATE note SET statut=:statut where id=:id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_POST['id']);
$stmt->bindParam(':statut', $_POST['statut']);
if (!empty($_POST['id'])) {
    if ($stmt->execute()) {
        $message = "Réussite du changement de la note pour : " . htmlspecialchars($_POST['nom']);
    } else {
        $message = "Erreur lors de du changement de la note.";
    }
} else {
    $message = "Vous ne changez pas de note. ";
}
$dbh = null;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>supprimer une note</title>
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
        <?php echo htmlspecialchars($decodedToken['preferred_username']); ?>
    </div>
    <div class="toprightrole">
        Rôles: <?php echo implode(', ', $roles); ?>
    </div>
    <div class="toprightlogo">
        <img src="user.png" alt="logo ariovis" height="50">
    </div>

    <h1 class="titre"><?= $message; ?></h1>
    <a href="./management.php" class="button-link">Retour</a><br>
    <a href="./hall.php" class="button-link">Accueil</a>
</body>
</html>
