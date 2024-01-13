<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Ta struna to bydzie ło niczym">
        <meta name="keywords" content="strona, ło, niczem">
        <meta name="author" content="Piotr Żywczak">
        <link rel="icon" type="image/x-icon" href="logo.png">
        <link rel="stylesheet" href="style.css">
        <title>Tu bydzie tytuł</title>
    </head>
    <body>
        <div id="logo">
            <img src="logo.png" alt="Tu powinno być logo">
        </div>
        <main>
            <h1>Logging in</h1>
            <form action="login.php" method="post">
                <input type="text" name="login" id="login" placeholder="login"><br>
                <input type="text" name="password" id="password" placeholder="password"><br>
                <input type="submit" id="submit">
            </form>  
            <p>
                <a href="reset.php">RESET PASSWORD</a>
                <a href="createaccount.php">CREATE NEW ACCOUNT</a>
                <hr>
                <span>or</span>
                <hr>
                <a href="google.com"><img src="oogle.png" alt="google">continue with Google</a>
            </p>
            
        </main>
    </body>
</html>