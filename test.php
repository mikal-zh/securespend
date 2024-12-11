<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

session_start();

$oidc = new OpenIDConnectClient(
    'https://login.microsoftonline.com/0bb01e5c-05ea-4c36-acbd-38c5854875ec/v2.0', // Remplace par votre Tenant ID
    '2e1ae597-42c8-4e58-95bb-5172b8c604b7',  // Remplace par votre Client ID pour Azure AD
    'b82985cd-8704-47ea-88d8-c0da4dc751f3'  // Remplace par votre Client Secret pour Azure AD
);

// Configurer les scopes nécessaires
/*$oidc->addScope('openid profile email');
$oidc->addScope('openid');
$oidc->addScope('profile');
$oidc->addScope('email');
*/
$oidc->setRedirectURL('https://securepsend.ariovis.fr/callback.php');

// Authentification avec Entra ID
$oidc->authenticate();

// Obtenir le token d'accès
$accessToken = $oidc->getAccessToken();
$_SESSION['access_token'] = $accessToken;

header('Location: log.php');
exit();
?>
