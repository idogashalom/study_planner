<?php

// Include necessary files
require_once __DIR__ . '/../config/Database.php';    // Handles the database connection
require_once __DIR__ . '/../model/Assignment.php';   // The Assignment model class

use Model\Assignment; // Use the Assignment class from the "Model" namespace

class AssignmentController {
    private $assignmentModel; // Will hold an instance of the Assignment model

    // This constructor runs automatically when the controller is used
    public function __construct() {
        session_start(); // Start or resume the user session

        // Check if the user is logged in by checking for 'email' in the session
        if (!isset($_SESSION['email'])) {
            http_response_code(401); // Send HTTP 401 Unauthorized error
            echo json_encode(['message' => 'Unauthorized']);
            exit(); // Stop further execution
        }

        // Create database connection and pass it to the model
        $db = new \Config\Database();
        $pdo = $db->getConnection(); // Get the PDO instance
        $this->assignmentModel = new Assignment($pdo); // Pass DB connection to model
    }

    // This function handles all assignment-related requests
    public function handleRequest() {
        $userEmail = $_SESSION['email']; // Get user's email from the session

        // Get the request body (only applies to POST requests)
        $data = json_decode(file_get_contents('php://input'), true);

        // Determine the action from the URL (?action=...) or request body
        $action = $_GET['action'] ?? ($data['action'] ?? '');

        // Based on the action value, do the appropriate task
        switch ($action) {

            // 🔹 Get all assignments for this user
            case 'get':
                echo json_encode($this->assignmentModel->getAssignmentsByUser($userEmail));
                break;

            // 🔹 Add a new assignment
            case 'add':
                $result = $this->assignmentModel->addAssignment(
                    $userEmail,
                    $data['title'],              // Required: title of the assignment
                    $data['subject'] ?? '',      // Optional: subject name
                    $data['due_date'],           // Required: due date
                    $data['priority'] ?? 'medium'// Optional: priority (default: medium)
                );
                echo json_encode(['success' => $result]);
                break;

            // 🔹 Update an existing assignment
            case 'update':
                $result = $this->assignmentModel->updateAssignment(
                    $data['id'],                 // ID of the assignment to update
                    $data['title'],              // New title
                    $data['subject'] ?? '',      // New subject (optional)
                    $data['due_date'],           // New due date
                    $data['priority'] ?? 'medium', // New priority
                    $data['completed'] ?? 0      // Completed status (0 or 1)
                );
                echo json_encode(['success' => $result]);
                break;

            // 🔹 Delete an assignment
            case 'delete':
                $result = $this->assignmentModel->deleteAssignment($data['id']);
                echo json_encode(['success' => $result]);
                break;

            // 🔹 Toggle completion status (done/not done)
            case 'toggle':
                $result = $this->assignmentModel->toggleComplete($data['id'], $data['completed']);
                echo json_encode(['success' => $result]);
                break;

            // 🔹 If action is unknown
            default:
                http_response_code(400); // Bad request
                echo json_encode(['message' => 'Invalid action']);
                break;
        }
    }
}
