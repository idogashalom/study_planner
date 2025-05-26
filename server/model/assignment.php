<?php
namespace Model;

class Assignment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get all assignments for a specific user, ordered by due date (soonest first)
    public function getAssignmentsByUser($email) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM assignments 
            WHERE user_email = :email 
            ORDER BY due_date ASC
        ");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Add a new assignment with default completion set to 0 (not completed)
    public function addAssignment($email, $title, $subject, $dueDate, $priority) {
        $stmt = $this->pdo->prepare("
            INSERT INTO assignments (user_email, title, subject, due_date, priority, completed) 
            VALUES (:email, :title, :subject, :due_date, :priority, 0)
        ");
        return $stmt->execute([
            'email' => $email,
            'title' => $title,
            'subject' => $subject,
            'due_date' => $dueDate,
            'priority' => $priority
        ]);
    }

    // Update an existing assignment (e.g. when editing task details)
    public function updateAssignment($id, $title, $subject, $dueDate, $priority, $completed) {
        $stmt = $this->pdo->prepare("
            UPDATE assignments 
            SET title = :title, 
                subject = :subject, 
                due_date = :due_date, 
                priority = :priority, 
                completed = :completed 
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'title' => $title,
            'subject' => $subject,
            'due_date' => $dueDate,
            'priority' => $priority,
            'completed' => $completed
        ]);
    }

    // Delete an assignment by its ID
    public function deleteAssignment($id) {
        $stmt = $this->pdo->prepare("DELETE FROM assignments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Mark an assignment as complete or incomplete (1 or 0)
    public function toggleComplete($id, $completed) {
        $stmt = $this->pdo->prepare("
            UPDATE assignments 
            SET completed = :completed 
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'completed' => $completed
        ]);
    }
}
