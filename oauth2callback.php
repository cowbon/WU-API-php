<?php
require_once __DIR__ . '/google-api-php-client-2.2.1/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfigFile('client_id.json');
$client->setRedirectUri('https://'. $_SERVER['HTTP_HOST'] .'/IoT/oauth2callback.php');
$client->addScope(Google_Service_Calendar::CALENDAR_READONLY);

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/IoT/googleoauth.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
?>
