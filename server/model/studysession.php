<?php
// server/model/StudySession.php

class StudySession {
    private $conn;
    private $table = 'study_sessions';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new study session for a user
    public function create($email, $title, $subject, $duration, $goal) {
        // Insert a new row with session details
        $query = "INSERT INTO {$this->table} (user_email, title, subject, duration, goal) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$email, $title, $subject, $duration, $goal]);
    }

    // Get all study sessions for a user; if status is given, filter by it (active or completed)
    public function getAll($email, $status = null) {
        $query = "SELECT * FROM {$this->table} WHERE user_email = ?";
        $params = [$email]; // Start with just the email

        // If a valid status is provided, add a condition to the SQL query
        if ($status && in_array($status, ['active','completed'])) {
            $query .= " AND status = ?";
            $params[] = $status;
        }

        // Show the most recent sessions first
        $query .= " ORDER BY start_time DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update the status of a study session (e.g., from 'active' to 'completed')
    public function updateStatus($id, $status) {
        try {
            $sql = "UPDATE {$this->table} 
                    SET status = ?, 
                        end_time = CASE WHEN ? = 'completed' THEN NOW() ELSE end_time END 
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);

            // If the status is 'completed', set the end_time to the current time (NOW())
            return $stmt->execute([$status, $status, $id]);

        } catch (PDOException $e) {
            // If there's an error (e.g., invalid ID), return a failure message
            return ['success' => false, 'message' => 'Failed to update status'];
        }
    }

    // Delete a study session by its ID
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    // Get today's statistics for the user: how many Pomodoro sessions and total focus time
    public function getStatsByEmail($email) {
        try {
            $today = date('Y-m-d'); // Get today's date in 'YYYY-MM-DD' format

            // Count how many sessions the user completed today
            $sqlPomodoros = "SELECT COUNT(*) FROM {$this->table} 
                             WHERE user_email = ? 
                               AND status = 'completed' 
                               AND DATE(end_time) = ?";
            $stmt = $this->conn->prepare($sqlPomodoros);
            $stmt->execute([$email, $today]);
            $pomodorosToday = (int) $stmt->fetchColumn(); // Get single value from column

            // Add up the durations (in minutes) of all sessions completed today
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
            return false; // If something goes wrong, return false
        }
    }

    // Fetch subject-based study analytics for the user, based on selected time range
    public function fetchAnalytics($email, $range) {
        $rangeSql = ''; // This will hold the WHERE clause depending on the selected range

        // Filter sessions within last 7 days
        if ($range === 'week') {
            $rangeSql = "AND start_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        }
        // Filter sessions within last 1 month
        elseif ($range === 'month') {
            $rangeSql = "AND start_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        }
        // Filter sessions within last 4 months (approximate semester)
        elseif ($range === 'semester') {
            $rangeSql = "AND start_date >= DATE_SUB(CURDATE(), INTERVAL 4 MONTH)";
        }

        // Fetch total number of sessions, total minutes, and total pomodoros grouped by subject
        $stmt = $this->conn->prepare("
            SELECT subject, 
                   COUNT(*) AS sessions, -- total number of sessions per subject
                   SUM(TIMESTAMPDIFF(MINUTE, start_date, end_time)) AS total_minutes, -- total time spent
                   SUM(pomodoros) AS total_pomodoros -- total pomodoros done
            FROM study_sessions
            WHERE email = ? $rangeSql
            GROUP BY subject
        ");

        $stmt->execute([$email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // return the result as an array of subjects + stats
    }
}
