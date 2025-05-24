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
  <title>Study Sessions - StudyMake</title>
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

    .sessions-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .sessions-title {
      font-size: 1.75rem;
      font-weight: 600;
    }

    .sessions-container {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1.5rem;
    }

    .sessions-card {
      background-color: var(--card-bg);
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      border: 1px solid var(--border);
    }

    /* Pomodoro Timer Styles */
    .pomodoro-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .timer-card {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      border-radius: 12px;
      padding: 1.5rem;
      text-align: center;
    }

    .timer-display {
      font-size: 4rem;
      font-weight: 600;
      margin: 1rem 0;
      font-family: 'Courier New', monospace;
    }

    .timer-controls {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .timer-btn {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      border: none;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      font-size: 1.25rem;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .timer-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: scale(1.05);
    }

    .timer-btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
      transform: none;
    }

    .timer-modes {
      display: flex;
      justify-content: center;
      gap: 0.5rem;
      margin-top: 1rem;
    }

    .mode-btn {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      border: none;
      border-radius: 20px;
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .mode-btn.active {
      background: white;
      color: var(--primary);
      font-weight: 500;
    }

    .session-form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      font-size: 0.875rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
      color: var(--text);
    }

    .form-control {
      padding: 0.75rem;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 0.9375rem;
      background-color: var(--bg);
      color: var(--text);
      transition: all 0.2s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .start-btn {
      grid-column: span 2;
      background-color: var(--success);
      color: white;
      padding: 0.875rem;
      border: none;
      border-radius: 8px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      margin-bottom: 2rem;
    }

    .start-btn:hover {
      background-color: #0e9d71;
    }

    .session-filters {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .filter-btn {
      padding: 0.5rem 1rem;
      border-radius: 20px;
      border: 1px solid var(--border);
      background-color: var(--bg);
      color: var(--text-light);
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .filter-btn.active {
      background-color: var(--primary-light);
      color: var(--primary);
      border-color: var(--primary);
    }

    .sessions-list {
      display: grid;
      gap: 1rem;
    }

    .session-item {
      display: grid;
      grid-template-columns: auto 1fr auto auto;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      border-radius: 8px;
      background-color: var(--bg);
      border: 1px solid var(--border);
    }

    .session-status {
      width: 12px;
      height: 12px;
      border-radius: 50%;
    }

    .status-active {
      background-color: var(--success);
      animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
      0% {
        opacity: 1;
      }

      50% {
        opacity: 0.5;
      }

      100% {
        opacity: 1;
      }
    }

    .status-completed {
      background-color: var(--success);
    }

    .status-paused {
      background-color: var(--warning);
    }

    .session-details {
      display: flex;
      flex-direction: column;
    }

    .session-title {
      font-weight: 500;
      margin-bottom: 0.25rem;
    }

    .session-meta {
      display: flex;
      gap: 1rem;
      font-size: 0.875rem;
      color: var(--text-light);
    }

    .session-subject {
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .session-time {
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .session-actions {
      display: flex;
      gap: 0.5rem;
    }

    .action-btn {
      background: none;
      border: none;
      color: var(--text-light);
      cursor: pointer;
      font-size: 1.1rem;
      transition: color 0.2s ease;
    }

    .action-btn:hover {
      color: var(--primary);
    }

    .empty-state {
      text-align: center;
      padding: 2rem;
      color: var(--text-light);
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 1.5rem;
      }

      .pomodoro-container {
        grid-template-columns: 1fr;
      }

      .session-form {
        grid-template-columns: 1fr;
      }

      .start-btn {
        grid-column: span 1;
      }

      .session-item {
        grid-template-columns: auto 1fr;
        grid-template-rows: auto auto;
      }

      .session-actions {
        margin-top: 1rem;
        grid-column: 2;
        justify-content: flex-end;
      }
    }
  </style>
</head>

<body>
  <!-- Include sidebar -->
  <?php include 'sidebar.php'; ?>

  <div class="main-content">
    <div class="sessions-header">
      <h1 class="sessions-title">Study Sessions</h1>
      <div class="user-greeting">
        <?php echo htmlspecialchars($_SESSION['name']); ?>'s Focus Time
      </div>
    </div>

    <div class="sessions-container">
      <div class="sessions-card">
        <!-- Pomodoro Timer -->
        <div class="pomodoro-container">
          <div class="timer-card">
            <h3><i class='bx bx-time-five'></i> Pomodoro Timer</h3>
            <div class="timer-display" id="timerDisplay">25:00</div>
            <div class="timer-controls">
              <button class="timer-btn" id="startTimer">
                <i class='bx bx-play'></i>
              </button>
              <button class="timer-btn" id="pauseTimer" disabled>
                <i class='bx bx-pause'></i>
              </button>
              <button class="timer-btn" id="resetTimer" disabled>
                <i class='bx bx-reset'></i>
              </button>
            </div>
            <div class="timer-modes">
              <button class="mode-btn active" data-mode="pomodoro">Focus (25:00)</button>
              <button class="mode-btn" data-mode="shortBreak">Short Break (5:00)</button>
              <button class="mode-btn" data-mode="longBreak">Long Break (15:00)</button>
            </div>
          </div>

          <!-- Session Stats -->
          <div class="timer-card" style="background: linear-gradient(135deg, #10B981 0%, #0e9d71 100%);">
            <h3><i class='bx bx-stats'></i> Session Stats</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
              <div>
                <div style="font-size: 2.5rem; font-weight: 600;">0</div>
                <div style="font-size: 0.875rem;">Pomodoros Today</div>
              </div>
              <div>
                <div style="font-size: 2.5rem; font-weight: 600;">0h 00m</div>
                <div style="font-size: 0.875rem;">Total Focus</div>
              </div>
            </div>
          </div>
        </div>

        <!-- New Session Form -->
        <form id="sessionForm" class="session-form">
          <div class="form-group">
            <label for="sessionTitle">Session Title</label>
            <input
              type="text"
              id="sessionTitle"
              class="form-control"
              placeholder="What are you studying?"
              required>
          </div>

          <div class="form-group">
            <label for="sessionSubject">Subject/Topic</label>
            <input
              type="text"
              id="sessionSubject"
              class="form-control"
              placeholder="Subject or topic name">
          </div>

          <div class="form-group">
            <label for="sessionDuration">Duration (minutes)</label>
            <input
              type="number"
              id="sessionDuration"
              class="form-control"
              min="5"
              max="120"
              value="25"
              required>
          </div>

          <div class="form-group">
            <label for="sessionGoal">Study Goal</label>
            <input
              type="text"
              id="sessionGoal"
              class="form-control"
              placeholder="What do you want to accomplish?">
          </div>

          <button type="submit" class="start-btn">
            <i class='bx bx-play-circle'></i> Start Study Session
          </button>
        </form>

        <!-- Session Filters -->
        <div class="session-filters">
          <button class="filter-btn active" data-filter="all">All</button>
          <button class="filter-btn" data-filter="active">Active</button>
          <button class="filter-btn" data-filter="completed">Completed</button>
          <button class="filter-btn" data-filter="today">Today</button>
        </div>

        <!-- Sessions List -->
        <div class="sessions-list" id="sessionsList">
          <!-- Sample Session Items -->
          <div class="session-item">
            <div class="session-status status-active"></div>
            <div class="session-details">
              <span class="session-title">Linear Algebra Concepts</span>
              <div class="session-meta">
                <span class="session-subject"><i class='bx bx-book'></i> Mathematics</span>
                <span class="session-time"><i class='bx bx-time'></i> 15/25 mins</span>
              </div>
            </div>
            <span style="font-size: 0.875rem; color: var(--success);">In Progress</span>
            <div class="session-actions">
              <button class="action-btn"><i class='bx bx-pause'></i></button>
              <button class="action-btn"><i class='bx bx-stop-circle'></i></button>
            </div>
          </div>

          <div class="session-item">
            <div class="session-status status-completed"></div>
            <div class="session-details">
              <span class="session-title">French Vocabulary Practice</span>
              <div class="session-meta">
                <span class="session-subject"><i class='bx bx-book'></i> French</span>
                <span class="session-time"><i class='bx bx-time'></i> 25/25 mins</span>
              </div>
            </div>
            <span style="font-size: 0.875rem; color: var(--text-light);">Completed</span>
            <div class="session-actions">
              <button class="action-btn"><i class='bx bx-refresh'></i></button>
            </div>
          </div>

          <div class="empty-state" id="emptyState" style="display: none;">
            <i class='bx bx-time' style="font-size: 2rem; margin-bottom: 1rem;"></i>
            <p>No study sessions found. Start one to begin tracking!</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../../js/pages/sidebar.js"></script>
  <script>
    // js/pages/sessions.js

    document.addEventListener('DOMContentLoaded', function() {
      const timerDisplay = document.getElementById('timerDisplay');
      const startBtn = document.getElementById('startTimer');
      const pauseBtn = document.getElementById('pauseTimer');
      const resetBtn = document.getElementById('resetTimer');
      const modeBtns = document.querySelectorAll('.mode-btn');
      const sessionForm = document.getElementById('sessionForm');
      const sessionsList = document.getElementById('sessionsList');
      const filterBtns = document.querySelectorAll('.filter-btn');
      const emptyState = document.getElementById('emptyState');

      let timer;
      let timeLeft = 25 * 60;
      let isRunning = false;
      let currentMode = 'pomodoro';
      let pomodoroCount = 0;

      const modes = {
        pomodoro: 25 * 60,
        shortBreak: 5 * 60,
        longBreak: 15 * 60
      };

      updateDisplay();
      fetchSessions('all');

      startBtn.addEventListener('click', startTimer);
      pauseBtn.addEventListener('click', pauseTimer);
      resetBtn.addEventListener('click', resetTimer);

      modeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          modeBtns.forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          currentMode = this.dataset.mode;
          resetTimer();
        });
      });

      sessionForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const title = document.getElementById('sessionTitle').value.trim();
        const subject = document.getElementById('sessionSubject').value.trim();
        const durationInput = document.getElementById('sessionDuration').value;
        const goal = document.getElementById('sessionGoal').value.trim();
        const duration = parseInt(durationInput, 10);

        if (!title || isNaN(duration) || duration <= 0) {
          alert('Please enter a valid title and duration.');
          return;
        }

        addSessionAPI({
          title,
          subject,
          duration,
          goal
        });
      });

      filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          filterBtns.forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          fetchSessions(this.dataset.filter);
        });
      });

      // API Calls

      function addSessionAPI(sessionData) {
        fetch('../../../server/route/sessionRoutes.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(sessionData)
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              fetchSessions('all');
              sessionForm.reset();
              if (!isRunning) {
                timeLeft = sessionData.duration * 60;
                updateDisplay();
                startTimer();
              }
            } else {
              alert('Failed to add session');
            }
          })
          .catch(() => alert('Error adding session'));
      }

      function fetchSessions(filter = 'all') {
        fetch(`../../../server/route/sessionRoutes.php?filter=${filter}`)
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              renderSessions(data.sessions);
            }
          })
          .catch(() => {
            sessionsList.innerHTML = '<p>Error loading sessions</p>';
          });
      }

      function fetchSessionStats() {
        fetch('../../../server/route/sessionRoutes.php?stats=true')
          .then(res => res.json())
          .then(data => {
            if (data.success && data.stats) {
              const stats = data.stats;
              const statsCard = document.querySelector('.timer-card[style*="#10B981"]');
              if (statsCard) {
                const statsHtml = `
      <h3><i class='bx bx-stats'></i> Session Stats</h3>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
        <div>
          <div style="font-size: 2.5rem; font-weight: 600;">${stats.pomodorosToday || 0}</div>
          <div style="font-size: 0.875rem;">Pomodoros Today</div>
        </div>
        <div>
          <div style="font-size: 2.5rem; font-weight: 600;">${stats.totalFocusMins || '0h 0m'}</div>
          <div style="font-size: 0.875rem;">Total Focus</div>
        </div>
      </div>
    `;
                statsCard.innerHTML = statsHtml;
              }
            }

          })
          .catch(() => {
            console.log('Failed to fetch session stats');
          });
      }

      fetchSessionStats();

      function updateStatusAPI(id, status) {
        fetch('../../../server/route/sessionRoutes.php', {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              id,
              status
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) fetchSessions('all');
            else alert('Failed to update session');
          });
      }

      function deleteSessionAPI(id) {
        fetch('../../../server/route/sessionRoutes.php', {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${id}`
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) fetchSessions('all');
            else alert('Failed to delete session');
          });
      }

      // Render UI

      function renderSessions(sessions) {
        if (!sessions.length) {
          emptyState.style.display = 'block';
          sessionsList.innerHTML = '';
          return;
        }

        emptyState.style.display = 'none';
        sessionsList.innerHTML = '';

        sessions.forEach(session => {
          const statusClass = session.status === 'active' ? 'status-active' : 'status-completed';
          const statusText = session.status === 'active' ? 'In Progress' : 'Completed';

          const sessionEl = document.createElement('div');
          sessionEl.className = 'session-item';

          sessionEl.innerHTML = `
        <div class="session-status ${statusClass}"></div>
        <div class="session-details">
          <span class="session-title">${escapeHTML(session.title)}</span>
          <div class="session-meta">
            <span class="session-subject"><i class='bx bx-book'></i> ${escapeHTML(session.subject || 'General')}</span>
            <span class="session-time"><i class='bx bx-time'></i> ${session.duration} mins</span>
          </div>
        </div>
        <span style="font-size: 0.875rem; color: var(--success);">${statusText}</span>
        <div class="session-actions">
          ${session.status === 'active' ? `
            <button class="action-btn pause-btn" data-id="${session.id}"><i class='bx bx-pause'></i></button>
            <button class="action-btn complete-btn" data-id="${session.id}"><i class='bx bx-stop-circle'></i></button>
          ` : `
            <button class="action-btn restart-btn" data-id="${session.id}"><i class='bx bx-refresh'></i></button>
            <button class="action-btn delete-btn" data-id="${session.id}"><i class='bx bx-trash'></i></button>
          `}
        </div>
      `;

          sessionsList.appendChild(sessionEl);
        });

        // Attach event listeners for action buttons
        sessionsList.querySelectorAll('.pause-btn').forEach(btn => {
          btn.addEventListener('click', e => {
            const id = e.currentTarget.dataset.id;
            updateStatusAPI(id, 'active'); // You could implement pause logic if needed
            alert('Pause feature to be implemented');
          });
        });

        sessionsList.querySelectorAll('.complete-btn').forEach(btn => {
          btn.addEventListener('click', e => {
            const id = e.currentTarget.dataset.id;
            updateStatusAPI(id, 'completed');
          });
        });

        sessionsList.querySelectorAll('.restart-btn').forEach(btn => {
          btn.addEventListener('click', e => {
            const id = e.currentTarget.dataset.id;
            updateStatusAPI(id, 'active');
          });
        });

        sessionsList.querySelectorAll('.delete-btn').forEach(btn => {
          btn.addEventListener('click', e => {
            if (confirm('Delete this session?')) {
              const id = e.currentTarget.dataset.id;
              deleteSessionAPI(id);
            }
          });
        });
      }




      // Timer functions same as your original code...

      function startTimer() {
        if (!isRunning) {
          isRunning = true;
          timer = setInterval(updateTimer, 1000);
          startBtn.disabled = true;
          pauseBtn.disabled = false;
          resetBtn.disabled = false;
        }
      }

      function pauseTimer() {
        if (isRunning) {
          clearInterval(timer);
          isRunning = false;
          startBtn.disabled = false;
          pauseBtn.disabled = true;
        }
      }

      function resetTimer() {
        pauseTimer();
        timeLeft = modes[currentMode];
        updateDisplay();
        resetBtn.disabled = true;
      }

      function updateTimer() {
        timeLeft--;
        updateDisplay();

        if (timeLeft <= 0) {
          clearInterval(timer);
          isRunning = false;
          startBtn.disabled = false;
          pauseBtn.disabled = true;
          resetBtn.disabled = false;

          if (currentMode === 'pomodoro') {
            pomodoroCount++;
            if (pomodoroCount % 4 === 0) {
              currentMode = 'longBreak';
            } else {
              currentMode = 'shortBreak';
            }
          } else {
            currentMode = 'pomodoro';
          }

          modeBtns.forEach(b => b.classList.remove('active'));
          document.querySelector(`.mode-btn[data-mode="${currentMode}"]`).classList.add('active');

          timeLeft = modes[currentMode];
          updateDisplay();

          // Notify user
          alert(`Time for ${currentMode.replace(/([A-Z])/g, ' $1').toLowerCase()}!`);
        }
      }

      function updateDisplay() {
        const minutes = Math.floor(timeLeft / 60).toString().padStart(2, '0');
        const seconds = (timeLeft % 60).toString().padStart(2, '0');
        timerDisplay.textContent = `${minutes}:${seconds}`;
      }

      // Escape HTML to prevent XSS
      function escapeHTML(text) {
        return text.replace(/[&<>"']/g, function(m) {
          return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
          } [m];
        });
      }

    });
  </script>

</body>

</html>