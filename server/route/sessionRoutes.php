<?php
// server/route/sessionRoutes.php
session_start();
if (!isset($_SESSION['email'])) {
  http_response_code(401);
  echo json_encode(['success' => false, 'message' => 'Unauthorized']);
  exit;
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controller/StudySessionController.php';

// Connect to DB
$db = new \Config\Database();
$pdo = $db->getConnection();
$controller = new StudySessionController($pdo);

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$email = $_SESSION['email'];

if ($method === 'POST') {
  // Add new session
  $data = json_decode(file_get_contents('php://input'), true);
  $result = $controller->addSession($email, $data);
  echo json_encode($result);
  exit;
}

if ($method === 'GET') {
  // Return stats if requested
  if (isset($_GET['stats']) && $_GET['stats'] === 'true') {
    $result = $controller->getStats($email);
    echo json_encode($result);
    exit;
  }

  // Otherwise get sessions with optional ?filter=active/completed/all
  $filter = $_GET['filter'] ?? null;
  $result = $controller->getSessions($email, $filter === 'all' ? null : $filter);
  echo json_encode($result);
  exit;
}

if ($method === 'PUT') {
  // Update session status, data contains {id, status}
  $data = json_decode(file_get_contents('php://input'), true);
  if (!isset($data['id'], $data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
  }
  $result = $controller->updateStatus($data['id'], $data['status']);
  echo json_encode($result);
  exit;
}

if ($method === 'DELETE') {
  // Delete session, id from query param ?id=...
  parse_str(file_get_contents("php://input"), $params);
  $id = $params['id'] ?? null;
  if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Missing id']);
    exit;
  }
  $result = $controller->deleteSession($id);
  echo json_encode($result);
  exit;
}




http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);

if (isset($_GET['action']) && $_GET['action'] === 'analytics') {
    $range = $_GET['range'] ?? 'week'; // 'week', 'month', 'semester', 'all'
    $result = $controller->getAnalyticsData($email, $range);
    echo json_encode($result);
    exit;
}