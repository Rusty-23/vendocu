<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>VenDocu - Login</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Style for modern look -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
            font-family: 'Roboto', sans-serif;
        }

        .login-container {
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .g_id_signin {
            margin-top: 20px;
            display: inline-block;
        }

        .login-logo {
            margin-bottom: 30px;
        }

        img {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>

<body>
    <!-- Container for login content -->
    <div class="login-container">
        <!-- Logo -->
        <div class="login-logo">
            <img src="image/vendocu_yellow.png" alt="VenDocu Logo" />
        </div>

        <!-- Title -->
        <h1>Welcome to VenDocu</h1>

        <?php
        if (isset($_GET['message'])) {
        ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_GET['message']; ?>
            </div>
        <?php
        }
        ?>

        <!-- Google Sign-In Button -->
        <div
            id="g_id_onload"
            data-client_id="70089890797-e9pmqvs239pog7ltvq054sgo0k88r351.apps.googleusercontent.com"
            data-context="signin"
            data-ux_mode="popup"
            data-callback="onSignIn"
            data-auto_select="true"
            data-itp_support="true"></div>

        <div class="g_id_signin"
            data-type="standard"
            data-shape="rectangular"
            data-theme="outline"
            data-text="signin_with"
            data-size="large"
            data-logo_alignment="center"></div>
    </div>

    <!-- JavaScript for Google Sign-In -->
    <script>
        function onSignIn(response) {
            // Add response.credential to cookie as g_token
            document.cookie = `g_token=${response.credential}; path=/;`;

            // Redirect to index.html
            window.location = 'redirect.php';
        }
    </script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</body>

</html>