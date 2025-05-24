<?php

require_once __DIR__ . '/../controller/LogoutController.php';

use Controller\LogoutController;

$controller = new LogoutController();
$controller->logout();
