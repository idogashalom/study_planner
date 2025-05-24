<?php 
// server/controller/StudySessionController.php

require_once __DIR__ . '/../model/StudySession.php';

class StudySessionController {
    private $model;

    public function __construct($db) {
        $this->model = new StudySession($db);
    }

    public function addSession($email, $data) {
        $title = trim($data['title'] ?? '');
        $subject = trim($data['subject'] ?? '');
        $duration = intval($data['duration'] ?? 0);
        $goal = trim($data['goal'] ?? '');

        if (!$title || $duration <= 0) {
            return ['success' => false, 'message' => 'Invalid input'];
        }

        $success = $this->model->create($email, $title, $subject, $duration, $goal);

        return ['success' => $success];
    }

    public function getSessions($email, $filter = null) {
        $sessions = $this->model->getAll($email, $filter);
        return ['success' => true, 'sessions' => $sessions];
    }

    public function updateStatus($id, $status) {
        $success = $this->model->updateStatus($id, $status);
        return ['success' => $success];
    }

    public function deleteSession($id) {
        $success = $this->model->delete($id);
        return ['success' => $success];
    }

    // New method to get user stats
    public function getStats($email) {
        $stats = $this->model->getStatsByEmail($email);
        if ($stats === false) {
            return ['success' => false, 'message' => 'Failed to retrieve stats'];
        }
        return ['success' => true, 'stats' => $stats];
    }

    public function getAnalyticsData($email, $range) {
    $stats = $this->model->fetchAnalytics($email, $range);
    if (!$stats) {
        return ['success' => false, 'message' => 'No data found'];
    }
    return ['success' => true, 'data' => $stats];
}

}
