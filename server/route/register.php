<?php
require_once('../config/database.php');
require_once('../model/user.php');
require_once('../controller/register.php');

use Controller\RegisterController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new RegisterController();
    $controller->register();

} else {
    echo "<script>alert('Invalid Route'); window.location.href='../../frontend/html/page/register.html';</script>";

}

?>