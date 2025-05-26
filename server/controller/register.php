<?php

namespace Controller; // This declares the namespace for the controller class

session_start(); // Start the session in case we need to store session data later

// Include the necessary PHP files for database connection and user model
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/user.php';

use Config\Database; // Use the Database class from the Config namespace
use Model\User;       // Use the User class from the Model namespace
use PDOException;     // For catching database-related errors

class RegisterController
{
    public function register()
    {
        // Check if the form was submitted using the POST method
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // STEP 1: Sanitize and collect form input
            $formData = [
                'name'     => trim($_POST['name'] ?? ''),       // Trim name input (remove spaces)
                'email'    => trim($_POST['email'] ?? ''),      // Trim email input
                'password' => $_POST['password'] ?? ''          // No trimming password for safety
            ];

            // STEP 2: Validate that none of the fields are empty
            if (empty($formData['name']) || empty($formData['email']) || empty($formData['password'])) {
                $msg = "Please fill in all fields.";
                // Use JavaScript to alert the user and redirect back to register page
                echo "<script>
                    alert(" . json_encode($msg) . ");
                    window.location.href='../../frontend/html/page/register.html';
                </script>";
                exit(); // Stop executing the rest of the PHP code
            }

            try {
                // STEP 3: Connect to the database
                $db = new Database();
                $conn = $db->getConnection();

                // STEP 4: Create a new User object with the connection
                $user = new User($conn);

                // STEP 5: Check if the email is already in use
                if ($user->emailExists($formData['email'])) {
                    $msg = "❌ Email is already registered.";
                    echo "<script>
                        alert(" . json_encode($msg) . ");
                        window.location.href='../../frontend/html/page/register.html';
                    </script>";
                    exit();
                }

                // STEP 6: Try to register the user
                if ($user->register($formData['name'], $formData['email'], $formData['password'])) {
                    $msg = "✅ Registration successful";
                    echo "<script>
                        alert(" . json_encode($msg) . ");
                        window.location.href='../../frontend/html/page/login.html'; // Go to login page
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
                // STEP 7: Catch and handle any database connection or query errors
                $msg = "Database Error: " . $e->getMessage();
                echo "<script>
                    alert(" . json_encode($msg) . ");
                    window.location.href='../../frontend/html/page/register.html';
                </script>";
                exit();
            }

        } else {
            // If the user tries to access this controller without POST request
            $msg = "Invalid request method.";
            echo "<script>
                alert(" . json_encode($msg) . ");
                window.location.href='../../frontend/html/page/register.html';
            </script>";
            exit();
        }
    }
}
