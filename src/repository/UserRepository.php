<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';

class UserRepository extends Repository
{
    public function isEmailUnique(string $email): bool
    {
        $stmt = $this->database->connect()->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() == 0;
    }

    public function isLoginUnique(string $login): bool
    {
        $stmt = $this->database->connect()->prepare('SELECT COUNT(*) FROM users WHERE login = :login');
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() == 0;
    }

    public function isEmailValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

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
            VALUES (DEFAULT, :name, :surname, :email, :login, :password, :type)
        ');

        $stmt->bindParam(':name', $user->getName(), PDO::PARAM_STR);
        $stmt->bindParam(':surname', $user->getSurname(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':login', $user->getLogin(), PDO::PARAM_STR);
        $stmt->bindParam(':password', $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindParam(':type', $user->getType(), PDO::PARAM_STR);

        if (!$stmt->execute()) {
            throw new Exception('Error adding user.');
        }
    }
}
