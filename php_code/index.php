<?php
require 'vendor/autoload.php';  // Assure-toi que ce chemin est correct

use Jumbojett\OpenIDConnectClient;
use Dotenv\Dotenv;

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$oidc = new OpenIDConnectClient(
    provider_url: $_ENV['OIDC_ISSUER_URL'], // Remplace par ton domaine Keycloak et le nom de ton Realm
    client_id: $_ENV['OIDC_CLIENT_ID'],  // Remplace par le Client ID que tu as créé
    client_secret: $_ENV['OIDC_CLIENT_SECRET']  // Remplace par le Secret Client que tu as obtenu
);

$oidc->authenticate();

$accessToken = $oidc->getAccessToken();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['access_token'] = $accessToken;

header('Location: hall.php');
exit();
?>
