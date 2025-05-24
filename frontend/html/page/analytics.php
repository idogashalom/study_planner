<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.html");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics - StudyMake</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/pages/sidebar.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    :root {
      --primary: #6366f1;
      --primary-dark: #4338ca;
      --primary-light: #E0E7FF;
      --text: #1F2937;
      --text-light: #6B7280;
      --border: #E5E7EB;
      --bg: #F9FAFB;
      --card-bg: #FFFFFF;
      --success: #10B981;
      --warning: #F59E0B;
      --error: #EF4444;
    }

    [data-theme="dark"] {
      --primary: #818CF8;
      --text: #F9FAFB;
      --text-light: #D1D5DB;
      --border: #374151;
      --bg: #111827;
      --card-bg: #1F2937;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg);
      color: var(--text);
      display: flex;
      min-height: 100vh;
    }

    .main-content {
      flex: 1;
      padding: 2rem;
      margin-left: 250px;
      transition: margin 0.3s ease;
    }

    .analytics-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .analytics-title {
      font-size: 1.75rem;
      font-weight: 600;
    }

    .time-range-selector {
      display: flex;
      gap: 0.5rem;
    }

    .range-btn {
      padding: 0.5rem 1rem;
      border-radius: 20px;
      border: 1px solid var(--border);
      background-color: var(--bg);
      color: var(--text-light);
      cursor: pointer;
      transition: all 0.2s ease;
      font-size: 0.875rem;
    }

    .range-btn.active {
      background-color: var(--primary-light);
      color: var(--primary);
      border-color: var(--primary);
    }

    .analytics-grid {
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 1.5rem;
    }

    .card {
      background-color: var(--card-bg);
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      border: 1px solid var(--border);
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .card-title {
      font-size: 1.125rem;
      font-weight: 600;
    }

    .card-value {
      font-size: 2rem;
      font-weight: 600;
      margin: 1rem 0;
    }

    .card-change {
      display: flex;
      align-items: center;
      gap: 0.25rem;
      font-size: 0.875rem;
    }

    .positive {
      color: var(--success);
    }

    .negative {
      color: var(--error);
    }

    .chart-container {
      position: relative;
      height: 300px;
      width: 100%;
    }

    .stats-card {
      grid-column: span 3;
    }

    .main-chart {
      grid-column: span 8;
    }

    .subject-chart {
      grid-column: span 4;
    }

    .time-chart {
      grid-column: span 6;
    }

    .productivity-chart {
      grid-column: span 6;
    }

    .subject-list {
      margin-top: 1rem;
    }

    .subject-item {
      display: flex;
      justify-content: space-between;
      padding: 0.75rem 0;
      border-bottom: 1px solid var(--border);
    }

    .subject-name {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .subject-color {
      width: 12px;
      height: 12px;
      border-radius: 50%;
    }

    .subject-time {
      font-weight: 500;
    }

    .empty-state {
      text-align: center;
      padding: 2rem;
      color: var(--text-light);
      grid-column: 1 / -1;
    }

    @media (max-width: 1200px) {
      .stats-card {
        grid-column: span 6;
      }
      
      .main-chart {
        grid-column: span 12;
      }
      
      .subject-chart {
        grid-column: span 6;
      }
      
      .time-chart,
      .productivity-chart {
        grid-column: span 12;
      }
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 1.5rem;
      }
      
      .stats-card {
        grid-column: span 12;
      }
      
      .subject-chart {
        grid-column: span 12;
      }
      
      .time-range-selector {
        flex-wrap: wrap;
      }
    }
  </style>
</head>
<body>
  <!-- Include sidebar -->
  <?php include 'sidebar.php'; ?>

  <div class="main-content">
    <div class="analytics-header">
      <h1 class="analytics-title">Study Analytics</h1>
      <div class="time-range-selector">
        <button class="range-btn active" data-range="week">This Week</button>
        <button class="range-btn" data-range="month">This Month</button>
        <button class="range-btn" data-range="semester">This Semester</button>
        <button class="range-btn" data-range="all">All Time</button>
      </div>
    </div>

    <div class="analytics-grid">
      <!-- Stats Cards -->
      <div class="card stats-card">
        <div class="card-header">
          <h2 class="card-title">Total Study Time</h2>
          <i class='bx bx-time'></i>
        </div>
        <div class="card-value">14h 25m</div>
        <div class="card-change positive">
          <i class='bx bx-up-arrow-alt'></i> 12% from last week
        </div>
      </div>

      <div class="card stats-card">
        <div class="card-header">
          <h2 class="card-title">Sessions Completed</h2>
          <i class='bx bx-check-circle'></i>
        </div>
        <div class="card-value">27</div>
        <div class="card-change positive">
          <i class='bx bx-up-arrow-alt'></i> 5% from last week
        </div>
      </div>

      <div class="card stats-card">
        <div class="card-header">
          <h2 class="card-title">Pomodoros</h2>
          <i class='bx bx-alarm'></i>
        </div>
        <div class="card-value">48</div>
        <div class="card-change negative">
          <i class='bx bx-down-arrow-alt'></i> 8% from last week
        </div>
      </div>

      <div class="card stats-card">
        <div class="card-header">
          <h2 class="card-title">Productivity Score</h2>
          <i class='bx bx-trending-up'></i>
        </div>
        <div class="card-value">82%</div>
        <div class="card-change positive">
          <i class='bx bx-up-arrow-alt'></i> 6% from last week
        </div>
      </div>

      <!-- Main Chart -->
      <div class="card main-chart">
        <div class="card-header">
          <h2 class="card-title">Study Time Trend</h2>
          <div>
            <button class="range-btn active" data-metric="time">Time</button>
            <button class="range-btn" data-metric="sessions">Sessions</button>
            <button class="range-btn" data-metric="pomodoros">Pomodoros</button>
          </div>
        </div>
        <div class="chart-container">
          <canvas id="trendChart"></canvas>
        </div>
      </div>

      <!-- Subject Distribution -->
      <div class="card subject-chart">
        <div class="card-header">
          <h2 class="card-title">By Subject</h2>
          <i class='bx bx-book'></i>
        </div>
        <div class="chart-container">
          <canvas id="subjectChart"></canvas>
        </div>
        <div class="subject-list">
          <div class="subject-item">
            <div class="subject-name">
              <span class="subject-color" style="background-color: #6366F1;"></span>
              <span>Mathematics</span>
            </div>
            <span class="subject-time">5h 22m</span>
          </div>
          <div class="subject-item">
            <div class="subject-name">
              <span class="subject-color" style="background-color: #10B981;"></span>
              <span>Science</span>
            </div>
            <span class="subject-time">3h 45m</span>
          </div>
          <div class="subject-item">
            <div class="subject-name">
              <span class="subject-color" style="background-color: #F59E0B;"></span>
              <span>History</span>
            </div>
            <span class="subject-time">2h 18m</span>
          </div>
          <div class="subject-item">
            <div class="subject-name">
              <span class="subject-color" style="background-color: #818CF8;"></span>
              <span>Language</span>
            </div>
            <span class="subject-time">1h 50m</span>
          </div>
        </div>
      </div>

      <!-- Time of Day Chart -->
      <div class="card time-chart">
        <div class="card-header">
          <h2 class="card-title">Time of Day</h2>
          <i class='bx bx-sun'></i>
        </div>
        <div class="chart-container">
          <canvas id="timeChart"></canvas>
        </div>
      </div>

      <!-- Productivity Chart -->
      <div class="card productivity-chart">
        <div class="card-header">
          <h2 class="card-title">Productivity Trend</h2>
          <i class='bx bx-bar-chart-alt'></i>
        </div>
        <div class="chart-container">
          <canvas id="productivityChart"></canvas>
        </div>
      </div>

      <div class="empty-state" id="emptyState" style="display: none;">
        <i class='bx bx-line-chart' style="font-size: 2rem; margin-bottom: 1rem;"></i>
        <p>No analytics data available yet. Complete some study sessions to see your insights!</p>
      </div>
    </div>
  </div>

  <script src="../../js/layout/sidebar.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Time range selector
      const rangeBtns = document.querySelectorAll('.range-btn');
      rangeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          if (this.classList.contains('active')) return;
          
          rangeBtns.forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          
          // In a real app, you would fetch data for the selected range
          updateCharts(this.dataset.range);
        });
      });
      
      // Initialize charts
      const trendCtx = document.getElementById('trendChart').getContext('2d');
      const subjectCtx = document.getElementById('subjectChart').getContext('2d');
      const timeCtx = document.getElementById('timeChart').getContext('2d');
      const productivityCtx = document.getElementById('productivityChart').getContext('2d');
      
      const trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
          labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
          datasets: [{
            label: 'Study Time (hours)',
            data: [2.5, 1.8, 3.2, 2.1, 1.5, 0.8, 2.1],
            borderColor: '#6366F1',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            borderWidth: 2,
            tension: 0.3,
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: 'rgba(0, 0, 0, 0.05)'
              }
            },
            x: {
              grid: {
                display: false
              }
            }
          }
        }
      });
      
      const subjectChart = new Chart(subjectCtx, {
        type: 'doughnut',
        data: {
          labels: ['Mathematics', 'Science', 'History', 'Language', 'Other'],
          datasets: [{
            data: [35, 25, 15, 12, 13],
            backgroundColor: [
              '#6366F1',
              '#10B981',
              '#F59E0B',
              '#818CF8',
              '#E5E7EB'
            ],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          cutout: '70%'
        }
      });
      
      const timeChart = new Chart(timeCtx, {
        type: 'bar',
        data: {
          labels: ['6-9 AM', '9-12 PM', '12-3 PM', '3-6 PM', '6-9 PM', '9-12 AM'],
          datasets: [{
            label: 'Study Time',
            data: [1.2, 3.5, 2.1, 4.2, 2.8, 0.5],
            backgroundColor: '#6366F1',
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: 'rgba(0, 0, 0, 0.05)'
              }
            },
            x: {
              grid: {
                display: false
              }
            }
          }
        }
      });
      
      const productivityChart = new Chart(productivityCtx, {
        type: 'line',
        data: {
          labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
          datasets: [{
            label: 'Productivity Score',
            data: [65, 72, 78, 82, 85],
            borderColor: '#10B981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            borderWidth: 2,
            tension: 0.3,
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: false,
              min: 50,
              max: 100,
              grid: {
                color: 'rgba(0, 0, 0, 0.05)'
              }
            },
            x: {
              grid: {
                display: false
              }
            }
          }
        }
      });
      
      // Chart metric toggle
      const metricBtns = document.querySelectorAll('.main-chart .range-btn');
      metricBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          metricBtns.forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          
          // Update chart data based on selected metric
          switch(this.dataset.metric) {
            case 'time':
              trendChart.data.datasets[0].label = 'Study Time (hours)';
              trendChart.data.datasets[0].data = [2.5, 1.8, 3.2, 2.1, 1.5, 0.8, 2.1];
              break;
            case 'sessions':
              trendChart.data.datasets[0].label = 'Sessions Completed';
              trendChart.data.datasets[0].data = [4, 3, 5, 4, 2, 1, 3];
              break;
            case 'pomodoros':
              trendChart.data.datasets[0].label = 'Pomodoros Completed';
              trendChart.data.datasets[0].data = [8, 6, 10, 7, 5, 3, 6];
              break;
          }
          trendChart.update();
        });
      });
      
      // In a real app, this would fetch data from your backend
      function updateCharts(range) {
        console.log(`Loading data for ${range}...`);
        // You would typically make an AJAX call here to get data for the selected range
        // Then update all charts with the new data
      }
    });
  </script>
</body>
</html>