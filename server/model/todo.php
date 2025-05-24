<?php

namespace Model;

use PDO;

class Todo
{
    private $conn;
    private $table = 'todos';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function getTodosByUser($userEmail)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_email = :email ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $userEmail]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTodo($userEmail, $task)
    {
        $sql = "INSERT INTO {$this->table} (user_email, task) VALUES (:email, :task)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['email' => $userEmail, 'task' => $task]);
    }

    public function updateTodo($id, $task)
    {
        $sql = "UPDATE {$this->table} SET task = :task WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['task' => $task, 'id' => $id]);
    }

    public function deleteTodo($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function toggleComplete($id, $completed)
    {
        $sql = "UPDATE {$this->table} SET completed = :completed WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['completed' => $completed ? 1 : 0, 'id' => $id]);
    }
}
