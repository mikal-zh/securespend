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

// Fonction pour décoder le token JWT
function decodeJWT($jwt) {
    $tokenParts = explode('.', $jwt);
    $tokenPayload = base64_decode($tokenParts[1]);
    return json_decode($tokenPayload, true); // Retourne le payload sous forme de tableau
}

$decodedToken = decodeJWT($accessToken);

$roles = isset($decodedToken['realm_access']['roles']) ? $decodedToken['realm_access']['roles'] : [];

if (!in_array('role_admin', $roles)) {
    // Rediriger vers la page 'hall.php' si l'utilisateur n'a pas le rôle 'user'
    header('Location: hall.php');
    exit(); // Terminer l'exécution du script
}

$dbh = new PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';port=' . $_ENV['DB_PORT'] . ';charset=utf8mb4',
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $dbh->query('SELECT * FROM note');
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$nb_notes = count($notes);
//$dbh = null;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

    <body style="background-color:#1E293B;">
    </body>

    <div class="top">
        <header>
            <div class="logotopleft">
                <img src="logo.png" alt="logo ariovis" width="50" height="50">
            </div>
            <div class="placetopleft">
                <h1>Management</h1>
            </div>
        </header>
    </div>
    <div class="toprightuser">
        <?php echo htmlspecialchars($decodedToken['preferred_username']); // Afficher le nom de l'utilisateur ?>
    </div>
    <div class="toprightrole">
        Rôles: <?php echo implode(', ', $roles); // Afficher les rôles de l'utilisateur ?>
    </div>
    <div class="toprightlogo">
        <img src="user.png" alt="logo ariovis" height="50">
    </div>

<a href="hall.php" class="button-link">accueil</a>

<table class="table">
<!--<form action="./valide_note.php" method="post">-->
    <thead>
        <tr>
            <th class="text-center" colspan="6"><?= $nb_notes?> note de frais</th>
        </tr>
        <tr class="table-info">
            <th>utilisateur</th>
            <th>nom</th>
            <th>montant</th>
            <th>valide</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($notes as $note): ?>
            <tr>
                <td><?= htmlspecialchars($note['user_id']) ?></td>
                <td><?= htmlspecialchars($note['nom']) ?></td>
                <td><?= htmlspecialchars($note['valeur']) ?></td>
                <td><?= htmlspecialchars($note['statut']) ?></th>
                <td>
		    <form action="./valide_note.php" method="post">
                    	<input type="hidden" name="statut" value="oui">
                    	<input type="hidden" name="id" value="<?= $note["id"] ?>">
                    	<input type="hidden" name="nom" value="<?= $note["nom"] ?>">
                    	<button type="submit" class="button-link">oui</button>
		    </form>
		</th>
		<th>
		    <form action="./valide_note.php" method="post">
                        <input type="hidden" name="statut" value="non">
                        <input type="hidden" name="id" value="<?= $note["id"] ?>">
                        <input type="hidden" name="nom" value="<?= $note["nom"] ?>">
                        <button type="submit" class="button-link">non</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
<!--    </form>-->
</table>

</body>
</html>
