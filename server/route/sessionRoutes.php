<?php
// Start the session so we can access the logged-in user's email
session_start();

// If the user is not logged in, block access
if (!isset($_SESSION['email'])) {
  http_response_code(401); // 401 Unauthorized
  echo json_encode(['success' => false, 'message' => 'Unauthorized']);
  exit;
}

// Load necessary files (Database connection and StudySessionController)
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controller/StudySessionController.php';

// Create a database connection
$db = new \Config\Database();
$pdo = $db->getConnection();

// Create the controller and pass in the database connection
$controller = new StudySessionController($pdo);

// Set the content type to JSON
header('Content-Type: application/json');

// Get the request method (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];
$email = $_SESSION['email']; // Get logged-in user's email

// ===========================
// 💾 Handle POST (Add Session)
// ===========================
if ($method === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true); // Get JSON body
  $result = $controller->addSession($email, $data);             // Add new session
  echo json_encode($result);                                    // Return result
  exit;
}

// ==========================
// 📥 Handle GET (Fetch Data)
// ==========================
if ($method === 'GET') {
  // If URL has ?stats=true, return user stats
  if (isset($_GET['stats']) && $_GET['stats'] === 'true') {
    $result = $controller->getStats($email);
    echo json_encode($result);
    exit;
  }

  // If URL has ?action=analytics, return chart analytics data
  if (isset($_GET['action']) && $_GET['action'] === 'analytics') {
    $range = $_GET['range'] ?? 'week'; // Accept range like week, month, semester, all
    $result = $controller->getAnalyticsData($email, $range);
    echo json_encode($result);
    exit;
  }

  // Otherwise, return the sessions (filtered or all)
  $filter = $_GET['filter'] ?? null; // filter can be "active", "completed", or "all"
  $result = $controller->getSessions($email, $filter === 'all' ? null : $filter);
  echo json_encode($result);
  exit;
}

// ==============================
// 🔁 Handle PUT (Update Status)
// ==============================
if ($method === 'PUT') {
  $data = json_decode(file_get_contents('php://input'), true);
  if (!isset($data['id'], $data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
  }
  $result = $controller->updateStatus($data['id'], $data['status']); // Update status
  echo json_encode($result);
  exit;
}

// =============================
// ❌ Handle DELETE (Remove Session)
// =============================
if ($method === 'DELETE') {
  parse_str(file_get_contents("php://input"), $params); // Get raw input (e.g., id=4)
  $id = $params['id'] ?? null;
  if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Missing id']);
    exit;
  }
  $result = $controller->deleteSession($id); // Delete session by ID
  echo json_encode($result);
  exit;
}

// ===============================
// 🚫 Method Not Allowed (Fallback)
// ===============================
http_response_code(405); // Method Not Allowed
echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
