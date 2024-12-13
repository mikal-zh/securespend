<?php
require 'vendor/autoload.php';  // Assure-toi que ce chemin est correct

use Jumbojett\OpenIDConnectClient;

$oidc = new OpenIDConnectClient(
    getenv('OIDC_ISSUER_KEYCLOAK'), // Remplace par ton domaine Keycloak et le nom de ton Realm
    getenv('OIDC_CLIENT_ID_KEYCLOAK'),  // Remplace par le Client ID que tu as créé
    getenv('OIDC_CLIENT_SECRET_KEYCLOAK')  // Remplace par le Secret Client que tu as obtenu
);

$oidc->authenticate();

$accessToken = $oidc->getAccessToken();

session_start();
$_SESSION['access_token'] = $accessToken;

header('Location: hall.php');
exit();
?>
