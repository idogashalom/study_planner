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
        // Ensure the form was submitted using POST method
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // Get and clean the input fields
            $formData = [
                'email' => trim($_POST['email'] ?? ''),       // Remove spaces from the start/end
                'password' => $_POST['password'] ?? ''         // Default to empty string if not set
            ];

            // If either field is empty, show error and redirect
            if (empty($formData['email']) || empty($formData['password'])) {
                $msg = "Please fill in both fields.";
                echo "<script>
                    alert(" . json_encode($msg) . ");
                    window.location.href='../../frontend/html/page/login.html';
                </script>";
                exit();
            }

            try {
                // Connect to database and initialize User model
                $db = new Database();
                $conn = $db->getConnection();
                $user = new User($conn);

                // Try to find a user with the entered email
                $userData = $user->getUserByEmail($formData['email']);

                // If user is not found, show error
                if (!$userData) {
                    $msg = "❌ Invalid email or password.";
                    echo "<script>
                        alert(" . json_encode($msg) . ");
                        window.location.href='../../frontend/html/page/login.html';
                    </script>";
                    exit();
                }

                // Check if the password matches the hashed one in the DB
                if (password_verify($formData['password'], $userData['password'])) {
                    // Save essential user info in session
                    $_SESSION['_id'] = $userData['id'];
                    $_SESSION['email'] = $userData['email'];
                    $_SESSION['name'] = $userData['name'];

                    // Redirect to sessions page with success message
                    $msg = "✅ Login successful!";
                    echo "<script>
                        alert(" . json_encode($msg) . ");
                        window.location.href='../../frontend/html/page/sessions.php';
                    </script>";
                    exit();
                } else {
                    // Wrong password
                    $msg = "❌ Invalid Email or password.";
                    echo "<script>
                        alert(" . json_encode($msg) . ");
                        window.location.href='../../frontend/html/page/login.html';
                    </script>";
                    exit();
                }

            } catch (PDOException $e) {
                // If there's a DB error, show it
                $msg = "Database Error: " . $e->getMessage();
                echo "<script>
                    alert(" . json_encode($msg) . ");
                    window.location.href='../../frontend/html/page/login.html';
                </script>";
                exit();
            }

        } else {
            // Someone tried to access this file directly via GET
            $msg = "Invalid request method.";
            echo "<script>
                alert(" . json_encode($msg) . ");
                window.location.href='../../frontend/html/page/login.html';
            </script>";
            exit();
        }
    }
}
