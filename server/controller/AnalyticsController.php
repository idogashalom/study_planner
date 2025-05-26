<?php
// Load the model file that handles database queries related to analytics
require_once '../model/AnalyticsModel.php';

// Define the AnalyticsController class
class AnalyticsController {
    private $model; // This will hold an instance of AnalyticsModel

    // Constructor runs automatically when this controller is created
    public function __construct($conn) {
        // Create an instance of the AnalyticsModel and pass in the DB connection
        $this->model = new AnalyticsModel($conn);
    }

    // This method handles fetching analytics data
    public function getAnalyticsData($email, $range) {
        // Fetch detailed stats from the model (e.g., list of sessions, assignments, etc.)
        $stats = $this->model->fetchAnalytics($email, $range);

        // Fetch summary data (e.g., total completed, average time, etc.)
        $summary = $this->model->fetchSummary($email, $range);

        // If no stats were found, return an error
        if (!$stats) {
            return ['success' => false, 'message' => 'No data found'];
        }

        // Return both the detailed stats and summary in the response
        return [
            'success' => true,
            'data' => $stats,       // Detailed data (like a list of sessions or usage patterns)
            'summary' => $summary   // Summary metrics (like total time spent, completion %, etc.)
        ];
    }
}
