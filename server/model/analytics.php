<?php
class AnalyticsModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn; // Store the database connection when the class is created
    }

    // Private helper function to return SQL time filters based on the requested range
    private function getRangeSQL($range) {
        switch ($range) {
            case 'week':
                // Past 7 days
                return "AND start_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            case 'month':
                // Past 1 month
                return "AND start_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
            case 'semester':
                // Past 4 months (typical semester length)
                return "AND start_date >= DATE_SUB(CURDATE(), INTERVAL 4 MONTH)";
            case 'all':
            default:
                // No filter (get everything)
                return "";
        }
    }

    // Fetch detailed analytics by subject for a user over a selected time range
    public function fetchAnalytics($email, $range) {
        $rangeSql = $this->getRangeSQL($range); // Get the appropriate date filter

        $stmt = $this->conn->prepare("
            SELECT subject, 
                   COUNT(*) AS sessions, -- total number of sessions per subject
                   SUM(TIMESTAMPDIFF(MINUTE, start_date, end_date)) AS total_minutes, -- total minutes studied
                   SUM(pomodoros) AS total_pomodoros -- total Pomodoro sessions
            FROM study_sessions
            WHERE email = ? $rangeSql
            GROUP BY subject -- group the result by subject so we can calculate stats per subject
        ");

        $stmt->execute([$email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // return all rows as associative array
    }

    // Fetch a summary (not grouped by subject) for total sessions, time, and pomodoros
    public function fetchSummary($email, $range) {
        $rangeSql = $this->getRangeSQL($range); // Apply time filter just like above

        $stmt = $this->conn->prepare("
            SELECT 
                COUNT(*) AS total_sessions, -- total number of study sessions
                SUM(TIMESTAMPDIFF(MINUTE, start_date, end_date)) AS total_minutes, -- total study time in minutes
                SUM(pomodoros) AS total_pomodoros -- total Pomodoro counts
            FROM study_sessions
            WHERE email = ? $rangeSql
        ");

        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // return just one row (summary)
    }
}
