<?php
// Load the necessary files
require_once('../config/database.php');        // Connects to your database
require_once('../model/user.php');             // Contains the User class with login logic
require_once('../controller/LoginController.php'); // Handles the login process

// Use the LoginController class from the Controller namespace
use Controller\LoginController;

// Check if the request came from a form using POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a new instance of the LoginController
    $controller = new LoginController();

    // Call the login method inside that controller
    $controller->login();
} else {
    // If someone accesses this route without submitting a form (e.g., with GET), show an alert
    echo "<script>
        alert('Invalid Route'); 
        window.location.href='../../frontend/html/page/login.html';
    </script>";
}
