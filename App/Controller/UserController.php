<?php

namespace App\Controller;

use App\Service\UserService;

class UserController
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function showLogin(?string $error = null)
    {
        require BASE_PATH . '/view/auth/login.php';
    }

    public function showRegister(?string $error = null)
    {
        require BASE_PATH . '/view/auth/register.php';
    }

    public function login()
    {
        $result = $this->service->login($_POST['email'] ?? '', $_POST['password'] ?? '');

        if (isset($result['error'])) {
            $error = $result['error'];
            require BASE_PATH . '/view/auth/login.php';
            return;
        }

        header('Location: ' . BASE_URL . '/Public/index.php?page=catalog');
        exit;
    }

    public function register()
    {
        $result = $this->service->register(
            $_POST['name'] ?? '',
            $_POST['email'] ?? '',
            $_POST['password'] ?? ''
        );

        if (isset($result['error'])) {
            $error = $result['error'];
            require BASE_PATH . '/view/auth/register.php';
            return;
        }

        header('Location: ' . BASE_URL . '/Public/index.php?page=login');
        exit;
    }

    public function logout()
    {
        $this->service->logout();
        header('Location: ' . BASE_URL . '/Public/index.php?page=login');
        exit;
    }
}