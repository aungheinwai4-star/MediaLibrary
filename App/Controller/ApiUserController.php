<?php

namespace App\Controller;

use App\Service\UserService;

class ApiUserController
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function login(): void
    {
        try {
            header('Content-Type: application/json');

            $input = json_decode(file_get_contents("php://input"), true);

            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';

            $result = $this->service->login($email, $password);

            if (isset($result['error'])) {
                http_response_code(401);
                echo json_encode([
                    'status' => 'error',
                    'message' => $result['error']
                ]);
                return;
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => $result
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Server error',
                'debug' => $e->getMessage()
            ]);
        }
    }

    public function register(): void
    {
        try {
            header('Content-Type: application/json');

            $input = json_decode(file_get_contents("php://input"), true);

            $name     = $input['name'] ?? '';
            $email    = $input['email'] ?? '';
            $password = $input['password'] ?? '';

            $result = $this->service->register($name, $email, $password);

            if (isset($result['error'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => $result['error']
                ]);
                return;
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => $result
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Server error',
                'debug' => $e->getMessage()
            ]);
        }
    }

    public function logout(): void
    {
        try {
            header('Content-Type: application/json');
            $this->service->logout();

            echo json_encode([
                'status' => 'success',
                'message' => 'Logged out successfully'
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Server error',
                'debug' => $e->getMessage()
            ]);
        }
    }
}
