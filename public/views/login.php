<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Ta struna to bydzie ło niczym">
        <meta name="keywords" content="strona, ło, niczem">
        <meta name="author" content="Piotr Żywczak">
        <link rel="icon" type="image/x-icon" href="../../public/img/logo.png">
        <link rel="stylesheet" type="text/css" href="../../public/css/style10.css">
        <title>Logowanie</title>
    </head>
    <body>
       <div class="container">
           <div id="logo">
                <img src="../../public/img/logo1.png" alt="Logo should be here">
           </div>
           <div id="login-container">
                <h3>Logowanie</h2>
                <form class="login" action="login" method="POST">
                   <input name="email" type="text" placeholder="email@email.com"><br>
                   <input name="password" type="password" placeholder="password"><br>
                   <span class="message">
                       <?php
                       if(isset($messages)){
                           foreach ($messages as $message) {
                               echo $message;
                           }
                       }
                       ?>
                   </span>
                   <input type="submit" value="Zaloguj">
                </form>
                <p>
                   <a href="register">Utwórz nowe konto</a>
                </p>
           </div>
       </div>
    </body>
</html>