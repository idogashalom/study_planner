<?php
class AnalyticsModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    private function getRangeSQL($range) {
        switch ($range) {
            case 'week':
                return "AND start_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            case 'month':
                return "AND start_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
            case 'semester':
                return "AND start_date >= DATE_SUB(CURDATE(), INTERVAL 4 MONTH)";
            case 'all':
            default:
                return "";
        }
    }

    public function fetchAnalytics($email, $range) {
        $rangeSql = $this->getRangeSQL($range);
        $stmt = $this->conn->prepare("
            SELECT subject, 
                   COUNT(*) AS sessions,
                   SUM(TIMESTAMPDIFF(MINUTE, start_date, end_date)) AS total_minutes,
                   SUM(pomodoros) AS total_pomodoros
            FROM study_sessions
            WHERE email = ? $rangeSql
            GROUP BY subject
        ");
        $stmt->execute([$email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchSummary($email, $range) {
        $rangeSql = $this->getRangeSQL($range);
        $stmt = $this->conn->prepare("
            SELECT 
                COUNT(*) AS total_sessions,
                SUM(TIMESTAMPDIFF(MINUTE, start_date, end_date)) AS total_minutes,
                SUM(pomodoros) AS total_pomodoros
            FROM study_sessions
            WHERE email = ? $rangeSql
        ");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
