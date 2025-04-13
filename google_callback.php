<?php
require_once 'vendor/autoload.php';
session_start();

// Set up Google OAuth client
$client = new Google_Client();
$client->setClientId('337917313584-emfbdjvs0rq1kpecucope0k92jrbmgen.apps.googleusercontent.com');
$client->setClientSecret('YOUR_GOOGLE_CLIENT_SECRET');
$client->setRedirectUri('http://localhost/google_callback.php');
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    // Get the Google access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Get user info from Google
    $oauth = new Google_Service_Oauth2($client);
    $userInfo = $oauth->userinfo->get();

    $_SESSION["username"] = $userInfo->name;
    $_SESSION["email"] = $userInfo->email;

    // Redirect to main1.html with sessionStorage flag
    echo "<script>
            sessionStorage.setItem('showPopup', 'true');
            window.location.href = 'main1.html';
          </script>";
    exit();
} else {
    echo "Google Login Failed!";
}
?>
