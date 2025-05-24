<?php

namespace Controller;

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/user.php';

use Config\Database;
use Model\User;
use PDOException;

class LoginController
{
    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Sanitize and trim input data
            $formData = [
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? ''
            ];

            // Validate required fields
            if (empty($formData['email']) || empty($formData['password'])) {
                $msg = "Please fill in both fields.";
                echo "<script>
                    alert(" . json_encode($msg) . ");
                    window.location.href='../../frontend/html/page/login.html';
                </script>";
                exit();
            }

            try {
                // Create DB and User objects
                $db = new Database();
                $conn = $db->getConnection();
                $user = new User($conn);

                // Check if the email exists
                $userData = $user->getUserByEmail($formData['email']);
                // Change to use 'email'
                if (!$userData) {
                    $msg = "❌ Invalid email or password.";
                    echo "<script>
                        alert(" . json_encode($msg) . ");
                        window.location.href='../../frontend/html/page/login.html';
                    </script>";
                    exit();
                }

                // Verify password
                if (password_verify($formData['password'], $userData['password'])) {
                    // Store safe user info in session
                    $_SESSION['_id'] = $userData['id'];
                    $_SESSION['email'] = $userData['email'];
                    $_SESSION['name'] = $userData['name']; 

                    $msg = "✅ Login successful!";
                    echo "<script>
        alert(" . json_encode($msg) . ");
        window.location.href='../../frontend/html/page/sessions.php';
    </script>";
                    exit();
                } else {
                    $msg = "❌ Invalid Email or password.";
                    echo "<script>
                        alert(" . json_encode($msg) . ");
                        window.location.href='../../frontend/html/page/login.html';
                    </script>";
                    exit();
                }
            } catch (PDOException $e) {
                $msg = "Database Error: " . $e->getMessage();
                echo "<script>
                    alert(" . json_encode($msg) . ");
                    window.location.href='../../frontend/html/page/login.html';
                </script>";
                exit();
            }
        } else {
            $msg = "Invalid request method.";
            echo "<script>
                alert(" . json_encode($msg) . ");
                window.location.href='../../frontend/html/page/login.html';
            </script>";
            exit();
        }
    }
}
