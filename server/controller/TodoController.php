<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/todo.php';

// use Config\Database;
use Model\Todo;


class TodoController
{
    private $todoModel;

    public function __construct()
    {
        $db = new \Config\Database(); 
        $pdo = $db->getConnection(); // ✅ Correct method name
        $this->todoModel = new Todo($pdo);
    }

public function handleRequest()
{
    session_start();
    if (!isset($_SESSION['email'])) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit();
    }

    $userEmail = $_SESSION['email'];
    $action = $_GET['action'] ?? '';

    // For POST/PUT, decode JSON body once
    $data = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
    }

    switch ($action) {
        case 'get':
            echo json_encode($this->todoModel->getTodosByUser($userEmail));
            break;

        case 'add':
            if (!$data || !isset($data['task']) || empty(trim($data['task']))) {
                http_response_code(400);
                echo json_encode(['message' => 'Task is required']);
                exit();
            }
            $result = $this->todoModel->addTodo($userEmail, $data['task']);
            echo json_encode(['success' => $result]);
            break;

        case 'update':
            if (!$data || !isset($data['id'], $data['task'])) {
                http_response_code(400);
                echo json_encode(['message' => 'ID and Task are required']);
                exit();
            }
            $result = $this->todoModel->updateTodo($data['id'], $data['task']);
            echo json_encode(['success' => $result]);
            break;

        case 'delete':
            // For delete you can send id via query string or JSON, adjust accordingly
            $id = $_GET['id'] ?? ($data['id'] ?? null);
            if (!$id) {
                http_response_code(400);
                echo json_encode(['message' => 'ID is required']);
                exit();
            }
            $result = $this->todoModel->deleteTodo($id);
            echo json_encode(['success' => $result]);
            break;

        case 'toggle':
            if (!$data || !isset($data['id'], $data['completed'])) {
                http_response_code(400);
                echo json_encode(['message' => 'ID and Completed status required']);
                exit();
            }
            $result = $this->todoModel->toggleComplete($data['id'], $data['completed']);
            echo json_encode(['success' => $result]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['message' => 'Invalid action']);
    }
}


}
