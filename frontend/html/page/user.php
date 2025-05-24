<?php
session_start();

if (!isset($_SESSION['email'])) {
  echo "<script>alert('Please log in first'); window.location.href='login.html';</script>";
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Profile - StudyMake</title>
  <link rel="stylesheet" href="../../css/pages/user.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
      --error: #EF4444;
    }

    [data-theme="dark"] {
      --primary: #818CF8;
      --primary-dark: #6366F1;
      --text: #F9FAFB;
      --text-light: #D1D5DB;
      --border: #374151;
      --bg: #111827;
      --card-bg: #1F2937;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg);
      color: var(--text);
      line-height: 1.6;
      min-height: 100vh;
    }

    header {
      background-color: var(--card-bg);
      padding: 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      border-bottom: 1px solid var(--border);
    }

    #welcome-msg {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--text);
    }

    .btn-secondary {
      background-color: var(--bg);
      color: var(--primary);
      padding: 0.75rem 1.25rem;
      border-radius: 8px;
      font-weight: 500;
      text-decoration: none;
      border: 1px solid var(--primary);
      transition: all 0.3s ease;
      display: inline-block;
    }

    .btn-secondary:hover {
      background-color: var(--primary-light);
    }

    .user-dashboard {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1.5rem;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
    }

    .card {
      background-color: var(--card-bg);
      border-radius: 12px;
      padding: 1.75rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      border: 1px solid var(--border);
    }

    .card h2 {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      color: var(--text);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .card h2 i {
      font-size: 1.5rem;
      color: var(--primary);
    }

    .user-info p {
      margin-bottom: 1rem;
      display: flex;
      gap: 0.5rem;
    }

    .user-info strong {
      font-weight: 500;
      min-width: 120px;
      color: var(--text-light);
    }

    .quick-links ul {
      list-style: none;
    }

    .quick-links li {
      margin-bottom: 0.75rem;
    }

    .quick-links a {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      color: var(--text);
      text-decoration: none;
      padding: 0.75rem;
      border-radius: 8px;
      transition: all 0.2s ease;
    }

    .quick-links a:hover {
      background-color: var(--primary-light);
      color: var(--primary);
      transform: translateX(5px);
    }

    .quick-links i {
      font-size: 1.25rem;
      width: 1.5rem;
      text-align: center;
    }

    @media (max-width: 768px) {
      .user-dashboard {
        grid-template-columns: 1fr;
      }
      
      header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
      }
      
      #welcome-msg {
        font-size: 1.25rem;
      }
    }
  </style>
</head>

<body>
  <header>
    <h1 id="welcome-msg">
      Welcome, <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'User'; ?> 🎓
    </h1>
    <a href="../../../server/controller/pages/logout.php" class="btn-secondary">
      <i class="fas fa-sign-out-alt"></i> Log Out
    </a>
  </header>

  <main class="user-dashboard">
    <section class="user-info card">
      <h2><i class="bx bx-user-circle"></i> My Profile</h2>
      <p><strong>Name:</strong> <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'N/A'; ?></p>
      <p><strong>Email:</strong> <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'N/A'; ?></p>
      <p><strong>Member Since:</strong> <?php echo isset($_SESSION['join_date']) ? htmlspecialchars($_SESSION['join_date']) : date('Y-m-d'); ?></p>
    </section>

    <section class="progress card">
      <h2><i class="bx bx-bar-chart"></i> Study Progress</h2>
      <p><strong>Sessions Completed:</strong> 12</p>
      <p><strong>Assignments Submitted:</strong> 8</p>
      <p><strong>Current Streak:</strong> 5 days</p>
    </section>

    <section class="quick-links card">
      <h2><i class="bx bx-fast-forward"></i> Quick Access</h2>
      <ul>
        <li><a href="../../html/page/sessions.html"><i class="bx bx-book"></i> My Sessions</a></li>
        <li><a href="../../../server/controller/page-session/fetch_assignments.php"><i class="bx bx-edit"></i> Assignments</a></li>
        <li><a href="../../../index.html"><i class="bx bx-calendar"></i> Study Planner</a></li>
        <li><a href="#"><i class="bx bx-cog"></i> Account Settings</a></li>
      </ul>
    </section>
  </main>

  <script>
    // You can uncomment and use this if you implement the fetch API later
    /*
    fetch('get_user_data.php')
      .then(res => res.json())
      .then(data => {
        document.querySelector('.user-info').innerHTML += `
          <p><strong>Username:</strong> ${data.username}</p>
          <p><strong>Email:</strong> ${data.email}</p>
          <p><strong>Member Since:</strong> ${data.joined}</p>
        `;
        document.querySelector('.progress').innerHTML += `
          <p><strong>Sessions Completed:</strong> ${data.sessions_completed}</p>
          <p><strong>Assignments Submitted:</strong> ${data.assignments}</p>
          <p><strong>Current Streak:</strong> ${data.streak} days</p>
        `;
      })
      .catch(() => console.log("Failed to load additional user data."));
    */
  </script>
</body>
</html>