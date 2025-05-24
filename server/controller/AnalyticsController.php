<?php
require_once '../model/AnalyticsModel.php';

class AnalyticsController {
    private $model;

    public function __construct($conn) {
        $this->model = new AnalyticsModel($conn);
    }

    public function getAnalyticsData($email, $range) {
        $stats = $this->model->fetchAnalytics($email, $range);
        $summary = $this->model->fetchSummary($email, $range);

        if (!$stats) {
            return ['success' => false, 'message' => 'No data found'];
        }

        return [
            'success' => true,
            'data' => $stats,
            'summary' => $summary
        ];
    }
}
