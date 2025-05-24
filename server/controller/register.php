<?php

namespace Controller;

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/user.php';

use Config\Database;
use Model\User;
use PDOException;

class RegisterController
{
    public function register()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Sanitize and trim input data
            $formData = [
                'name'     => trim($_POST['name'] ?? ''),
                'email'    => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? ''
            ];

            // Validate required fields
            if (empty($formData['name']) || empty($formData['email']) || empty($formData['password'])) {
                $msg = "Please fill in all fields.";
                echo "<script>
                    alert(" . json_encode($msg) . ");
                    window.location.href='../../frontend/html/page/register.html';
                </script>";
                exit();
            }

            try {
                // Create DB and User objects
                $db = new Database();
                $conn = $db->getConnection();
                $user = new User($conn);

                // Check if email already exists
                if ($user->emailExists($formData['email'])) {
                    $msg = "❌ Email is already registered.";
                    echo "<script>
                        alert(" . json_encode($msg) . ");
                        window.location.href='../../frontend/html/page/register.html';
                    </script>";
                    exit();
                }

                // Attempt to register the user
                if ($user->register($formData['name'], $formData['email'], $formData['password'])) {
                    $msg = "✅ Registration successful";
                    echo "<script>
                        alert(" . json_encode($msg) . ");
                        window.location.href='../../frontend/html/page/login.html';
                    </script>";
                    exit();
                } else {
                    $msg = "❌ Registration failed. Try again.";
                    echo "<script>
                        alert(" . json_encode($msg) . ");
                        window.location.href='../../frontend/html/page/register.html';
                    </script>";
                    exit();
                }
            } catch (PDOException $e) {
                $msg = "Database Error: " . $e->getMessage();
                echo "<script>
                    alert(" . json_encode($msg) . ");
                    window.location.href='../../frontend/html/page/register.html';
                </script>";
                exit();
            }
        } else {
            $msg = "Invalid request method.";
            echo "<script>
                alert(" . json_encode($msg) . ");
                window.location.href='../../frontend/html/page/register.html';
            </script>";
            exit();
        }
    }
}
