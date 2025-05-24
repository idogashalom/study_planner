<?php
require_once('../config/database.php');
require_once('../model/user.php');
require_once('../controller/LoginController.php');

use Controller\LoginController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new LoginController();
    $controller->login();
} else {
    echo "<script>alert('Invalid Route'); window.location.href='../../frontend/html/page/login.html';</script>";
}
