<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';

class UserRepository extends Repository
{
    public function isEmailUnique($email)
    {
        $stmt = $this->database->connect()->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() == 0;
    }

    public function isLoginUnique($login)
    {
        $stmt = $this->database->connect()->prepare('SELECT COUNT(*) FROM users WHERE login = :login');
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() == 0;
    }

    public function isEmailValid($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function getUser($email)
    {
        $stmt = $this->database->connect()->prepare('SELECT * FROM users WHERE email = ?');

        $stmt->execute([
            $email
        ]);

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        return new User(
            $userData['id'],
            $userData['email'],
            $userData['password'],
            $userData['name'],
            $userData['surname'],
            $userData['login'],
            $userData['type']
        );
    }

    public function addUser(User $user)
    {
        if (!$this->isEmailUnique($user->getEmail())) {
            throw new Exception('Email is not unique.');
        }

        if (!$this->isLoginUnique($user->getLogin())) {
            throw new Exception('Login is not unique.');
        }

        if (!$this->isEmailValid($user->getEmail())) {
            throw new Exception('Invalid email format.');
        }

        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (id, name, surname, email, login, password, type)
            VALUES (DEFAULT, ?, ?, ?, ?, ?, ?)
        ');

        if(!$stmt->execute([
            $user->getName(),
            $user->getSurname(),
            $user->getEmail(),
            $user->getLogin(),
            $user->getPassword(),
            $user->getType()
        ])){
            throw new Exception('Error adding user');
        }
    }
}
