<?php

// Include required files
require_once __DIR__ . '/../config/Database.php'; // For connecting to the database
require_once __DIR__ . '/../model/todo.php';      // The Todo model that handles database logic

use Model\Todo; // Use the Todo class inside the Model namespace

class TodoController
{
    private $todoModel; // This variable holds an instance of the Todo model

    // Constructor is called when the controller is created
    public function __construct()
    {
        // Create a new database connection
        $db = new \Config\Database(); 
        $pdo = $db->getConnection(); // Get the PDO connection from the database class

        // Create an instance of the Todo model using the database connection
        $this->todoModel = new Todo($pdo);
    }

    // This method handles different types of requests
    public function handleRequest()
    {
        // Start the session to check if user is logged in
        session_start();
        if (!isset($_SESSION['email'])) {
            // If user is not logged in, return an "Unauthorized" message
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            exit();
        }

        // Get the user's email from the session
        $userEmail = $_SESSION['email'];

        // Get the "action" parameter from the URL (e.g., ?action=get)
        $action = $_GET['action'] ?? '';

        // Read and decode JSON body if it's a POST or PUT request
        $data = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
        }

        // Perform actions based on the value of "action"
        switch ($action) {

            // 🔹 GET To-dos
            case 'get':
                // Get all todos for the current user and return them as JSON
                echo json_encode($this->todoModel->getTodosByUser($userEmail));
                break;

            // 🔹 ADD a To-do
            case 'add':
                // Check if task data is valid
                if (!$data || !isset($data['task']) || empty(trim($data['task']))) {
                    http_response_code(400);
                    echo json_encode(['message' => 'Task is required']);
                    exit();
                }
                // Call the model to add the task
                $result = $this->todoModel->addTodo($userEmail, $data['task']);
                echo json_encode(['success' => $result]);
                break;

            // 🔹 UPDATE a To-do
            case 'update':
                // Make sure both ID and task text are present
                if (!$data || !isset($data['id'], $data['task'])) {
                    http_response_code(400);
                    echo json_encode(['message' => 'ID and Task are required']);
                    exit();
                }
                // Call the model to update the task
                $result = $this->todoModel->updateTodo($data['id'], $data['task']);
                echo json_encode(['success' => $result]);
                break;

            // 🔹 DELETE a To-do
            case 'delete':
                // Get the ID from either the URL or the JSON body
                $id = $_GET['id'] ?? ($data['id'] ?? null);
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['message' => 'ID is required']);
                    exit();
                }
                // Call the model to delete the task
                $result = $this->todoModel->deleteTodo($id);
                echo json_encode(['success' => $result]);
                break;

            // 🔹 TOGGLE a To-do's completion status
            case 'toggle':
                // Check that both ID and completed status are given
                if (!$data || !isset($data['id'], $data['completed'])) {
                    http_response_code(400);
                    echo json_encode(['message' => 'ID and Completed status required']);
                    exit();
                }
                // Call the model to toggle the completion status
                $result = $this->todoModel->toggleComplete($data['id'], $data['completed']);
                echo json_encode(['success' => $result]);
                break;

            // 🔹 DEFAULT case for invalid action
            default:
                http_response_code(400);
                echo json_encode(['message' => 'Invalid action']);
        }
    }
}
