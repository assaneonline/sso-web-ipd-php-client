Installation options :

1. Require the [composer](https://getcomposer.org/) package :

```sh
composer require assaneonline/sso-web-ipd-php-client
````

2. Clone this repository from GitHub :

```sh
git clone https://github.com/assaneonline/sso-web-ipd-php-client
```

then include the autoload in your PHP project :

```php
require_once(path_to_this/vendor/autoload.php)
```

Here is an example of how to authenticate a user with the SSO :

```php

$redirect_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$ssoClient = new SSOWeb\Client([
    'application_id' => '<REPLACE_WITH_YOUR_APPLICATION_ID>',
    'application_secret' => '<REPLACE_WITH_YOUR_APPLICATION_SECRET>',
    'redirect_url' => $redirect_url,
]);

// Redirect user to SSO web page 
$ssoClient->authenticate();

// ... then on the redirect page, verify session :
if($ssoClient->verify()){
   // User is authenticated
   // ...
}else{
    // Authentication failed
    // ///
}

```