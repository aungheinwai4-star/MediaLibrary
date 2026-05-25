<?php

namespace App\Inc;

class Auth
{
    public static function requireLogin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }
    }
}
