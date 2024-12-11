<?php
require 'vendor/autoload.php';
use Jumbojett\OpenIDConnectClient;

session_start();

$oidc = new OpenIDConnectClient(
    'https://login.microsoftonline.com/0bb01e5c-05ea-4c36-acbd-38c5854875ec/v2.0', // Remplace par votre Tenant ID
    '2e1ae597-42c8-4e58-95bb-5172b8c604b7',  // Remplace par votre Client ID pour Azure AD
    'b82985cd-8704-47ea-88d8-c0da4dc751f3'  // Remplace par votre Client Secret pour Azure AD
);

$oidc->setRedirectURL('https://securespend.ariovis.fr/callback_azure.php');
$oidc->addScope('openid profile email');

$oidc->authenticate();
$accessToken = $oidc->getAccessToken();

$_SESSION['access_token'] = $accessToken;

echo'test';

header('Location: log.php');
exit();
?>
