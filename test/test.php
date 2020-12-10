<?php

require __DIR__."/../vendor/autoload.php";

$application_id = "sso-web-demo";
$application_secret = "8a737173-a967-3cc8-8d9f-f74f9efcde70";

$redirect_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$ssoClient = new SSOWeb\Client([
    'application_id' => '',
    'application_secret' => '',
    'redirect_url' => $redirect_url,
    'verbose' => true
]);

// Authenticate user :
$ssoClient->authenticate();