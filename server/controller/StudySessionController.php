<?php 
// This file defines the StudySessionController class
// It acts as a "middle-man" between the frontend and the StudySession model logic

require_once __DIR__ . '/../model/StudySession.php'; // Include the model that handles database queries for sessions

class StudySessionController {
    private $model;

    // Constructor receives a database connection and uses it to initialize the model
    public function __construct($db) {
        $this->model = new StudySession($db); // Create a StudySession model instance with DB connection
    }

    // This method adds a new study session
    public function addSession($email, $data) {
        // Get and clean the input values from $data (usually from form or JSON)
        $title = trim($data['title'] ?? '');
        $subject = trim($data['subject'] ?? '');
        $duration = intval($data['duration'] ?? 0); // Convert duration to an integer
        $goal = trim($data['goal'] ?? '');

        // Check if title or duration are missing or invalid
        if (!$title || $duration <= 0) {
            return ['success' => false, 'message' => 'Invalid input'];
        }

        // Call the model to insert the session into the database
        $success = $this->model->create($email, $title, $subject, $duration, $goal);

        return ['success' => $success]; // Return success status
    }

    // This method fetches all sessions for a specific user (email)
    public function getSessions($email, $filter = null) {
        // You can pass a filter (e.g. subject or date) optionally
        $sessions = $this->model->getAll($email, $filter);
        return ['success' => true, 'sessions' => $sessions];
    }

    // This method updates the status of a session (e.g. to "completed")
    public function updateStatus($id, $status) {
        $success = $this->model->updateStatus($id, $status); // Call model to update
        return ['success' => $success];
    }

    // This method deletes a session using its ID
    public function deleteSession($id) {
        $success = $this->model->delete($id); // Delete session from DB
        return ['success' => $success];
    }

    // This method gets overall stats for a user (like total sessions, minutes, etc.)
    public function getStats($email) {
        $stats = $this->model->getStatsByEmail($email);
        if ($stats === false) {
            return ['success' => false, 'message' => 'Failed to retrieve stats'];
        }
        return ['success' => true, 'stats' => $stats];
    }

    // This method fetches study analytics based on a time range
    // Range can be: week, month, semester, or all
    public function getAnalyticsData($email, $range) {
        $stats = $this->model->fetchAnalytics($email, $range);
        if (!$stats) {
            return ['success' => false, 'message' => 'No data found'];
        }
        return ['success' => true, 'data' => $stats];
    }
}
