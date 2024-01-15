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
        <title>Rejestracja</title>
    </head>
    <body>
       <div class="container">
           <div id="logo">
               <img src="../../public/img/logo1.png" alt="Logo should be here">
           </div>
           <div id="register-container">
                <h3>Rejestracja</h2>
                <form class="register" action="register" method="POST">
                    <input name="login" type="text" placeholder="login">
                    <input name="email" type="text" placeholder="email@email.com">
                    <input name="password" type="password" placeholder="password">
                    <input name="confirmedPassword" type="password" placeholder="confirm password">
                    <input name="name" type="text" placeholder="name">
                    <input name="surname" type="text" placeholder="surname">
                    <span class="message">
                       <?php
                       if(isset($messages)){
                           foreach ($messages as $message) {
                               echo $message;
                           }
                       }
                       ?>
                    </span>
                    <input type="submit" value="Zarejestruj">
               </form>
               <p>
                   <a href="login">Masz konto? Zaloguj się</a>
               </p>
           </div>
       </div>
    </body>
</html>