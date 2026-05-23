<?php

namespace App\Service;

use App\Contract\UserRepositoryInterface;

class UserService
{
    private UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register($name, $email, $password)
    {
        if ($this->userRepo->findByEmail($email)) {
            return ['error' => 'Email already exists'];
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $this->userRepo->create([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ]);

        return ['success' => true];
    }

    public function login($email, $password)
    {
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            return ['error' => 'User not found'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['error' => 'Invalid password'];
        }

        $_SESSION['user'] = $user;

        return ['success' => true];
    }

    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
    }
}