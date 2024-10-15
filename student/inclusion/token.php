<?php $token = $_COOKIE["g_token"];
if (!$token) {
    header("Location: ../login.php");
    exit();
}

$googleClient = new GoogleClient(
    "VenDocu",
    "70089890797-e9pmqvs239pog7ltvq054sgo0k88r351.apps.googleusercontent.com",
    "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",
    "http://localhost/xampploc/black/home.php"
);

$googleClient->getClient()->addScope("email");
$googleClient->getClient()->addScope("profile");

$token = $googleClient->getClient()->verifyIdToken($token);
if (!$token) {
    setcookie('g_token', '', -1, '/');
    if (isset($_COOKIE['g_token'])) {
        unset($_COOKIE['g_token']); 
    }
    header("Location: ../login.php");
    exit();
}
?>