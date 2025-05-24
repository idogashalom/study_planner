<?php
// This will be included in all dashboard pages
?>
<div class="sidebar">
  <div class="sidebar-header">
    <h2>StudyMake</h2>
  </div>

  <nav class="sidebar-nav">
    <ul>
      <!-- <li>
        <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
          <i class='bx bx-home'></i>
          <span>Dashboard</span>
        </a>
      </li> -->
      <!-- <li>
        <a href="profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : '' ?>">
          <i class='bx bx-user'></i>
          <span>Profile</span>
        </a>
      </li> -->
      <li>
        <a href="sessions.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'sessions.php' ? 'active' : '' ?>">
          <i class='bx bx-book'></i>
          <span>Sessions</span>
        </a>
      </li>
      <li>
        <a href="assignments.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'assignments.php' ? 'active' : '' ?>">
          <i class='bx bx-edit'></i>
          <span>Assignments</span>
        </a>
      </li>
      <li>
        <a href="todo.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'todo.php' ? 'active' : '' ?>">
          <i class='bx bx-check-circle'></i>
          <span>To-Do List</span>
        </a>
      </li>
      <li>
        <a href="analytics.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'analytics.php' ? 'active' : '' ?>">
          <i class='bx bx-bar-chart'></i>
          <span>Analytics</span>
        </a>
      </li>
    </ul>

    <div class="sidebar-footer">
      <a href="../../../server/route/logoutRoutes.php" onclick="return confirm('Are you sure you want to logout?')" class="logout-btn">

        <i class='bx bx-log-out'></i>
        <span>Log Out</span>
      </a>
    </div>
  </nav>
</div>