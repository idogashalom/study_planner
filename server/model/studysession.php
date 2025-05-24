<?php
// server/model/StudySession.php

class StudySession {
    private $conn;
    private $table = 'study_sessions';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new session
    public function create($email, $title, $subject, $duration, $goal) {
        $query = "INSERT INTO {$this->table} (user_email, title, subject, duration, goal) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$email, $title, $subject, $duration, $goal]);
    }

    // Get sessions for a user, optionally filtered by status
    public function getAll($email, $status = null) {
        $query = "SELECT * FROM {$this->table} WHERE user_email = ?";
        $params = [$email];

        if ($status && in_array($status, ['active','completed'])) {
            $query .= " AND status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY start_time DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update session status
   public function updateStatus($id, $status) {
    try {
        $sql = "UPDATE {$this->table} SET status = ?, end_time = CASE WHEN ? = 'completed' THEN NOW() ELSE end_time END WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$status, $status, $id]);

        return ['success' => true, 'message' => 'Status updated'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Failed to update status'];
    }
}


    // Delete session
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    // New method to get aggregated stats for a user (pomodoros today, total focus time)
   public function getStatsByEmail($email) {
    try {
        $today = date('Y-m-d');

        // Count completed sessions today
        $sqlPomodoros = "SELECT COUNT(*) FROM {$this->table} 
                         WHERE user_email = ? 
                           AND status = 'completed' 
                           AND DATE(end_time) = ?";
        $stmt = $this->conn->prepare($sqlPomodoros);
        $stmt->execute([$email, $today]);
        $pomodorosToday = (int) $stmt->fetchColumn();

        // Sum durations of completed sessions today
        $sqlFocus = "SELECT COALESCE(SUM(duration), 0) FROM {$this->table} 
                     WHERE user_email = ? 
                       AND status = 'completed' 
                       AND DATE(end_time) = ?";
        $stmt = $this->conn->prepare($sqlFocus);
        $stmt->execute([$email, $today]);
        $totalFocusMins = (int) $stmt->fetchColumn();

        return [
            'pomodorosToday' => $pomodorosToday,
            'totalFocusMins' => $totalFocusMins,
        ];
    } catch (PDOException $e) {
        return false;
    }
}

public function fetchAnalytics($email, $range) {
    $rangeSql = '';
    if ($range === 'week') {
        $rangeSql = "AND start_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    } elseif ($range === 'month') {
        $rangeSql = "AND start_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
    } elseif ($range === 'semester') {
        $rangeSql = "AND start_date >= DATE_SUB(CURDATE(), INTERVAL 4 MONTH)";
    }

    $stmt = $this->conn->prepare("
        SELECT subject, 
               COUNT(*) AS sessions,
               SUM(TIMESTAMPDIFF(MINUTE, start_date, end_time)) AS total_minutes,
               SUM(pomodoros) AS total_pomodoros
        FROM study_sessions
        WHERE email = ? $rangeSql
        GROUP BY subject
    ");
    $stmt->execute([$email]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
