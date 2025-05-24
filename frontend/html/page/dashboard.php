<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.html");
  exit();
}

// Database connection would be established here
// $db = new PDO(...);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - StudyMake</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/pages/sidebar.css">
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

    .dashboard-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .dashboard-title {
      font-size: 1.75rem;
      font-weight: 600;
    }

    .card-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1.5rem;
    }

    .card {
      background-color: var(--card-bg);
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      border: 1px solid var(--border);
    }

    .card h3 {
      font-size: 1.25rem;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .item-list {
      list-style: none;
    }

    .item {
      padding: 0.75rem 0;
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .item:last-child {
      border-bottom: none;
    }

    .item-title {
      font-weight: 500;
    }

    .item-meta {
      font-size: 0.875rem;
      color: var(--text-light);
      display: flex;
      gap: 0.75rem;
    }

    .item-actions {
      display: flex;
      gap: 0.5rem;
    }

    .action-btn {
      background: none;
      border: none;
      color: var(--text-light);
      cursor: pointer;
      font-size: 1rem;
      transition: color 0.2s ease;
    }

    .action-btn:hover {
      color: var(--primary);
    }

    .priority-high {
      color: var(--error);
    }

    .priority-medium {
      color: var(--warning);
    }

    .priority-low {
      color: var(--success);
    }

    .status-active {
      color: var(--success);
    }

    .status-completed {
      color: var(--text-light);
      text-decoration: line-through;
    }

    .empty-state {
      text-align: center;
      padding: 1rem;
      color: var(--text-light);
      font-size: 0.875rem;
    }

    .view-all {
      margin-top: 1rem;
      text-align: right;
    }

    .view-all a {
      color: var(--primary);
      text-decoration: none;
      font-size: 0.875rem;
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <!-- Including the sidebar -->
  <?php include 'sidebar.php'; ?>

  <div class="main-content">
    <div class="dashboard-header">
      <h1 class="dashboard-title">Dashboard</h1>
      <div class="user-greeting">
        Welcome back, <?php echo htmlspecialchars($_SESSION['name']); ?>!
      </div>
    </div>

    <div class="card-container">
      <!-- Today's Sessions Card -->
      <div class="card">
        <h3><i class='bx bx-time'></i> Today's Sessions</h3>
        <ul class="item-list">
          <?php
          // Sample data - replace with actual database query
          $todaySessions = [
            ['title' => 'Math Study', 'subject' => 'Algebra', 'time' => '3:00 PM - 4:30 PM', 'status' => 'upcoming'],
            ['title' => 'History Review', 'subject' => 'World History', 'time' => '7:00 PM - 8:00 PM', 'status' => 'upcoming'],
            ['title' => 'French Practice', 'subject' => 'Language', 'time' => '9:00 AM - 10:00 AM', 'status' => 'completed']
          ];
          
          if (empty($todaySessions)) {
            echo '<li class="empty-state">No sessions scheduled for today</li>';
          } else {
            foreach ($todaySessions as $session) {
              $statusClass = $session['status'] === 'completed' ? 'status-completed' : 'status-active';
              echo '
              <li class="item">
                <div>
                  <div class="item-title '.$statusClass.'">'.$session['title'].'</div>
                  <div class="item-meta">
                    <span><i class="bx bx-book"></i> '.$session['subject'].'</span>
                    <span><i class="bx bx-time"></i> '.$session['time'].'</span>
                  </div>
                </div>
                <div class="item-actions">
                  <button class="action-btn"><i class="bx bx-play"></i></button>
                </div>
              </li>
              ';
            }
          }
          ?>
        </ul>
        <div class="view-all">
          <a href="sessions.php">View all sessions <i class='bx bx-chevron-right'></i></a>
        </div>
      </div>
      
      <!-- Pending Tasks Card -->
      <div class="card">
        <h3><i class='bx bx-task'></i> Pending Tasks</h3>
        <ul class="item-list">
          <?php
          // Sample data - replace with actual database query
          $pendingTasks = [
            ['title' => 'Complete math assignment', 'due' => 'Tomorrow', 'priority' => 'high'],
            ['title' => 'Read chapter 5 of biology', 'due' => 'Friday', 'priority' => 'medium'],
            ['title' => 'Prepare presentation slides', 'due' => 'Next Monday', 'priority' => 'low']
          ];
          
          if (empty($pendingTasks)) {
            echo '<li class="empty-state">No pending tasks</li>';
          } else {
            foreach ($pendingTasks as $task) {
              $priorityClass = 'priority-'.$task['priority'];
              echo '
              <li class="item">
                <div>
                  <div class="item-title">'.$task['title'].'</div>
                  <div class="item-meta">
                    <span><i class="bx bx-calendar"></i> Due: '.$task['due'].'</span>
                    <span class="'.$priorityClass.'">'.ucfirst($task['priority']).' priority</span>
                  </div>
                </div>
                <div class="item-actions">
                  <button class="action-btn"><i class="bx bx-check"></i></button>
                  <button class="action-btn"><i class="bx bx-edit"></i></button>
                </div>
              </li>
              ';
            }
          }
          ?>
        </ul>
        <div class="view-all">
          <a href="todo.php">View all tasks <i class='bx bx-chevron-right'></i></a>
        </div>
      </div>
      
      <!-- Upcoming Assignments Card -->
      <div class="card">
        <h3><i class='bx bx-edit'></i> Upcoming Assignments</h3>
        <ul class="item-list">
          <?php
          // Sample data - replace with actual database query
          $upcomingAssignments = [
            ['title' => 'Linear Algebra Problem Set', 'subject' => 'Math', 'due' => 'Tomorrow', 'progress' => '40%'],
            ['title' => 'History Essay Draft', 'subject' => 'History', 'due' => 'In 3 days', 'progress' => '15%'],
            ['title' => 'Chemistry Lab Report', 'subject' => 'Science', 'due' => 'Next Week', 'progress' => '0%']
          ];
          
          if (empty($upcomingAssignments)) {
            echo '<li class="empty-state">No upcoming assignments</li>';
          } else {
            foreach ($upcomingAssignments as $assignment) {
              echo '
              <li class="item">
                <div>
                  <div class="item-title">'.$assignment['title'].'</div>
                  <div class="item-meta">
                    <span><i class="bx bx-book"></i> '.$assignment['subject'].'</span>
                    <span><i class="bx bx-calendar"></i> Due: '.$assignment['due'].'</span>
                  </div>
                  <div class="progress-bar" style="margin-top: 0.5rem; height: 4px; background: var(--border); border-radius: 2px;">
                    <div style="width: '.$assignment['progress'].'; height: 100%; background: var(--primary); border-radius: 2px;"></div>
                  </div>
                </div>
                <div class="item-actions">
                  <button class="action-btn"><i class="bx bx-edit"></i></button>
                </div>
              </li>
              ';
            }
          }
          ?>
        </ul>
        <div class="view-all">
          <a href="assignments.php">View all assignments <i class='bx bx-chevron-right'></i></a>
        </div>
      </div>
      
      <!-- Quick Stats Card -->
      <div class="card">
        <h3><i class='bx bx-bar-chart-alt-2'></i> Quick Stats</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
          <div style="background: var(--primary-light); padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.5rem; font-weight: 600; color: var(--primary);">4</div>
            <div style="font-size: 0.875rem; color: var(--text-light);">Sessions Today</div>
          </div>
          <div style="background: var(--primary-light); padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.5rem; font-weight: 600; color: var(--primary);">7</div>
            <div style="font-size: 0.875rem; color: var(--text-light);">Pending Tasks</div>
          </div>
          <div style="background: var(--primary-light); padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.5rem; font-weight: 600; color: var(--primary);">3</div>
            <div style="font-size: 0.875rem; color: var(--text-light);">Upcoming Assignments</div>
          </div>
          <div style="background: var(--primary-light); padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.5rem; font-weight: 600; color: var(--primary);">82%</div>
            <div style="font-size: 0.875rem; color: var(--text-light);">Productivity</div>
          </div>
        </div>
        <div class="view-all" style="margin-top: 1.5rem;">
          <a href="analytics.php">View detailed analytics <i class='bx bx-chevron-right'></i></a>
        </div>
      </div>
    </div>
  </div>

  <script src="../../js/layout/sidebar.js"></script>
  <script>
    // You would add interactive functionality here
    document.addEventListener('DOMContentLoaded', function() {
      // Example: Mark task as complete
      document.querySelectorAll('.item-actions .bx-check').forEach(btn => {
        btn.addEventListener('click', function() {
          const item = this.closest('.item');
          item.style.opacity = '0.5';
          // In real app, you would make an AJAX call to update the task status
          setTimeout(() => {
            item.remove();
            // Check if list is empty and show empty state
          }, 300);
        });
      });
    });
  </script>
</body>
</html>