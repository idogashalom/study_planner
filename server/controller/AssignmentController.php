<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Assignment.php';

use Model\Assignment;

class AssignmentController {
    private $assignmentModel;

    public function __construct() {
        session_start();
        if (!isset($_SESSION['email'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            exit();
        }

        $db = new \Config\Database();
        $pdo = $db->getConnection();
        $this->assignmentModel = new Assignment($pdo);
    }

    public function handleRequest() {
        $userEmail = $_SESSION['email'];

        // For POST expect JSON input
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $_GET['action'] ?? ($data['action'] ?? '');

        switch ($action) {
            case 'get':
                echo json_encode($this->assignmentModel->getAssignmentsByUser($userEmail));
                break;

            case 'add':
                $result = $this->assignmentModel->addAssignment(
                    $userEmail,
                    $data['title'],
                    $data['subject'] ?? '',
                    $data['due_date'],
                    $data['priority'] ?? 'medium'
                );
                echo json_encode(['success' => $result]);
                break;

            case 'update':
                $result = $this->assignmentModel->updateAssignment(
                    $data['id'],
                    $data['title'],
                    $data['subject'] ?? '',
                    $data['due_date'],
                    $data['priority'] ?? 'medium',
                    $data['completed'] ?? 0
                );
                echo json_encode(['success' => $result]);
                break;

            case 'delete':
                $result = $this->assignmentModel->deleteAssignment($data['id']);
                echo json_encode(['success' => $result]);
                break;

            case 'toggle':
                $result = $this->assignmentModel->toggleComplete($data['id'], $data['completed']);
                echo json_encode(['success' => $result]);
                break;

            default:
                http_response_code(400);
                echo json_encode(['message' => 'Invalid action']);
                break;
        }
    }
}
