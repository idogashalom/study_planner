<?php
session_start();
require_once '../config/Database.php';
require_once '../controller/AnalyticsController.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$conn = new \Config\Database();
$pdo = $conn->getConnection();
$controller = new AnalyticsController($pdo);

if ($_GET['action'] === 'analytics') {
    $range = $_GET['range'] ?? 'week';
    $email = $_SESSION['email'];
    echo json_encode($controller->getAnalyticsData($email, $range));
}
