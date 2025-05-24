<?php

namespace Controller;

session_start();

class LogoutController
{
    public function logout()
    {
        // Unset all session variables
        $_SESSION = [];

        // Destroy the session
        session_destroy();

        // Alert and redirect
        $msg = "✅ You have been logged out.";
        echo "<script>
            alert(" . json_encode($msg) . ");
            window.location.href='../../frontend/html/page/login.html';
        </script>";
        exit();
    }
}
