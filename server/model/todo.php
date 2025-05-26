<?php

namespace Model;

use PDO;

class Todo
{
    private $conn; // Will hold the database connection
    private $table = 'todos'; // Table name in the database

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // Get all todo items for a specific user (by email)
    public function getTodosByUser($userEmail)
    {
        // Select all todos that belong to this user's email, most recent first
        $sql = "SELECT * FROM {$this->table} WHERE user_email = :email ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql); // Prepare the SQL statement
        $stmt->execute(['email' => $userEmail]); // Run the statement with the actual user email

        // Return all todos as an array of associative arrays
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new todo item for a user
    public function addTodo($userEmail, $task)
    {
        // Insert a new row into the todos table with the user email and task description
        $sql = "INSERT INTO {$this->table} (user_email, task) VALUES (:email, :task)";
        $stmt = $this->conn->prepare($sql);

        // Execute the statement with the provided email and task text
        return $stmt->execute(['email' => $userEmail, 'task' => $task]);
    }

    // Update the text of an existing todo item
    public function updateTodo($id, $task)
    {
        // Change the task content for the todo with the specified ID
        $sql = "UPDATE {$this->table} SET task = :task WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        // Provide the new task text and the ID of the todo to update
        return $stmt->execute(['task' => $task, 'id' => $id]);
    }

    // Delete a todo item by its ID
    public function deleteTodo($id)
    {
        // Remove the todo with the given ID from the database
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        // Provide the ID of the todo you want to delete
        return $stmt->execute(['id' => $id]);
    }

    // Mark a todo as completed or not completed
    public function toggleComplete($id, $completed)
    {
        // Update the 'completed' status (1 for true, 0 for false)
        $sql = "UPDATE {$this->table} SET completed = :completed WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        // Convert the boolean value to 1 (true) or 0 (false) before saving
        return $stmt->execute(['completed' => $completed ? 1 : 0, 'id' => $id]);
    }
}
