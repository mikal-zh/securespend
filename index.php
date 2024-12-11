<?php
require 'vendor/autoload.php';  // Assure-toi que ce chemin est correct

use Jumbojett\OpenIDConnectClient;

$oidc = new OpenIDConnectClient(
    'https://ariovisdemo.cloud-iam.com/realms/securespend', // Remplace par ton domaine Keycloak et le nom de ton Realm
    'securespend',  // Remplace par le Client ID que tu as créé
    '3Tm08I8t01QVXrwqyxoW0Ub3emZK2eag'  // Remplace par le Secret Client que tu as obtenu
);

$oidc->authenticate();

$accessToken = $oidc->getAccessToken();

session_start();
$_SESSION['access_token'] = $accessToken;

header('Location: hall.php');
exit();
?>
