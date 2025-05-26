<?php

namespace Controller;

session_start(); // Start the session so we can access and destroy it

class LogoutController
{
    public function logout()
    {
        // Step 1: Remove all session variables (clear the $_SESSION array)
        $_SESSION = [];

        // Step 2: Completely destroy the session on the server
        session_destroy();

        // Step 3: Show a logout success message and redirect the user to the login page
        $msg = "✅ You have been logged out.";
        echo "<script>
            alert(" . json_encode($msg) . ");
            window.location.href='../../frontend/html/page/login.html';
        </script>";
        exit(); // Stop script execution after redirect
    }
}
