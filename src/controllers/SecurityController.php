<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController
{
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function login()
    {
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $messages = [];

        if (empty($email) || empty($password)) {
            $messages[] = 'Uzupełnij wszystkie pola!<br>';
        }else if (strpos($email, '@') === false) {
            $messages[] = 'Niepoprawny format e-maila!';
        }else{
            $user = $this->userRepository->getUser($email);

            if (!$user) {
                $messages[] = 'Nie znaleziono użytkownika!<br>';
            }else if($user->getEmail() !== $email) {
                $messages[] = 'Użytkownik o tym e-mailu nie istnieje!<br>';
            }else if (!password_verify($password, $user->getPassword())) {
                $messages[] = 'Nieprawidłowe hasło!<br>';
            }
        }
        
        if (!empty($messages)) {
            return $this->render('login', ['messages' => $messages]);
        }

        $_SESSION['user_ID'] = $user->getID();
        $_SESSION['user_name'] = $user->getName();
        $_SESSION['user_surname'] = $user->getSurname();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_type'] = $user->getType();

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");
    }

    public function logout()
    {
        // Zakończenie sesji
        session_unset();
        session_destroy();
        
        // Przekierowanie na stronę logowania
        header("Location: /login"); // Możesz dostosować ścieżkę do własnych potrzeb
    }

    public function register()
    {
        if (!$this->isPost()) {
            return $this->render('register');
        }

        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmedPassword = $_POST['confirmedPassword'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        
        $messages = [];

        if (empty($login) || empty($email) || empty($password) || empty($confirmedPassword) || empty($name) || empty($surname)) {
            $messages[] = 'Uzupełnij wszystkie pola<br>';
        }else if ($password !== $confirmedPassword) {
            $messages[] = 'Hasła sa różne<br>';
        }else if (!$this->userRepository->isEmailUnique($email)) {
            $messages[] = 'Taki e-mail już istnieje<br>';
        }else if (!$this->userRepository->isLoginUnique($login)) {
            $messages[] = 'Taki login już istnieje<br>';
        }else if (!$this->userRepository->isEmailValid($email)) {
            $messages[] = 'Niepoprawny format e-maila<br>';
        }

    if (!empty($messages)) {
        return $this->render('register', ['messages' => $messages]);
    }

    $user = new User($id, $email, password_hash($password, PASSWORD_BCRYPT), $name, $surname, $login, 'user');

    $this->userRepository->addUser($user);

    return $this->render('login', ['messages' => ['Rejestracja przebiegła pomyślnie']]);
}
}
