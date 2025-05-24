document.addEventListener('DOMContentLoaded', function() {

  // FullCalendar Setup
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    initialDate: '2025-04-12',
    height: 400
  });
  calendar.render();

  // Dark Mode Toggle
  document.getElementById('toggle-theme').addEventListener('click', function() {
    document.body.classList.toggle('dark');
  });

  // Open Popups
  document.getElementById('open-session-form').addEventListener('click', () => {
    document.getElementById('session-form').style.display = 'flex';
  });

  document.getElementById('open-assignment-form').addEventListener('click', () => {
    document.getElementById('assignment-form').style.display = 'flex';
  });

  // Save Study Session
  document.getElementById('save-session').addEventListener('click', () => {
    let title = document.getElementById('session-title').value;
    let subject = document.getElementById('session-subject').value;
    let start = document.getElementById('session-start').value;
    let end = document.getElementById('session-end').value;
    if (title && start && end) {
      calendar.addEvent({
        title: title,
        start: `2025-04-12T${start}`,
        end: `2025-04-12T${end}`
      });
      addUpcomingSession(title, "April 12, 2025", start, end, "Planned");
      closePopup('session-form');
    }
  });

  // Save Assignment
  document.getElementById('save-assignment').addEventListener('click', () => {
    let subject = document.getElementById('assignment-subject').value;
    let date = document.getElementById('assignment-date').value;
    if (subject && date) {
      setTimeout(() => {
        document.getElementById('alarm-sound').play();
        alert(`Reminder: Assignment "${subject}" is due today!`);
      }, 5000); // play alarm after 5 seconds for demo
      closePopup('assignment-form');
    }
  });

  const renderAssignments = () => {
    assignmentList.innerHTML = "";
    assignments.forEach((a, i) => {
      const li = document.createElement("li");
      li.innerHTML = `
        <form action="send_assignment.php" method="POST" enctype="multipart/form-data" class="send-form">
          <b>${a.title}</b> - Due: <span style="color:red">${a.due}</span><br>
          <input type="hidden" name="assignment_title" value="${a.title}">
          <label>Upload:</label>
          <input type="file" name="assignment_file" required>
          <input type="email" name="student_email" placeholder="Your Email" required>
          <button type="submit">📤 Send</button>
          <button type="button" onclick="deleteAssignment(${i})">🗑️</button>
        </form>
      `;
      assignmentList.appendChild(li);
    });
  };
  

  // To-Do List
  document.getElementById('add-todo').addEventListener('click', () => {
    let input = document.getElementById('todo-input');
    if (input.value.trim() !== "") {
      let task = document.createElement('label');
      task.innerHTML = `<input type="checkbox"> ${input.value}`;
      document.getElementById('task-list').appendChild(task);
      input.value = "";
    }
  });

});

// Helper: Close Popups
function closePopup(id) {
  document.getElementById(id).style.display = 'none';
}

// Helper: Add Upcoming Session
function addUpcomingSession(subject, date, startTime, endTime, status) {
  let container = document.getElementById('upcoming-sessions');
  let session = document.createElement('div');
  session.classList.add('session');
  session.innerHTML = `
    <p><strong>${subject}</strong> - ${date}<br>${startTime}-${endTime}</p>
    <span class="badge planned">${status}</span>
  `;
  container.appendChild(session);
}

  // SESSION FORM SUBMISSION + LOCAL STORAGE BACKUP
  const addSessionForm = document.getElementById("addSessionForm");
  addSessionForm.addEventListener("submit", (e) => {
    const formData = new FormData(addSessionForm);
    const sessionData = Object.fromEntries(formData.entries());

    // Check required fields
    if (!sessionData.title || !sessionData.subject) {
      alert("All fields are required.");
      e.preventDefault();
      return;
    }

    // Save to localStorage backup (in case backend fails)
    let sessions = JSON.parse(localStorage.getItem("sessions")) || [];
    sessions.push(sessionData);
    localStorage.setItem("sessions", JSON.stringify(sessions));
  });

  // FULLCALENDAR INITIALIZATION
  if (typeof FullCalendar !== "undefined") {
    const calendarEl = document.getElementById("calendar");
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'timeGridWeek,timeGridDay'
      },
      events: '/load_sessions_json.php' // returns JSON of saved sessions
    });
    calendar.render();
  } else {
    document.getElementById("calendar").innerHTML =
      "<p style='color: gray;'>Calendar integration active. FullCalendar script required.</p>";
  }

  // POMODORO TIMER (25 min focus, 5 min break)
  let pomodoroTimer, isWorking = true, timerSeconds = 1500;
  const startPomodoro = () => {
    clearInterval(pomodoroTimer);
    pomodoroTimer = setInterval(() => {
      timerSeconds--;
      if (timerSeconds <= 0) {
        isWorking = !isWorking;
        timerSeconds = isWorking ? 1500 : 300;
        alert(isWorking ? "Back to focus!" : "Time for a short break!");
      }
      updatePomodoroDisplay();
    }, 1000);
  };

  const updatePomodoroDisplay = () => {
    const minutes = Math.floor(timerSeconds / 60).toString().padStart(2, '0');
    const seconds = (timerSeconds % 60).toString().padStart(2, '0');
    document.querySelector("#pomodoroDisplay")?.textContent = `${minutes}:${seconds}`;
  };

  // STUB FOR GROUP STUDY STATUS
  const groupStudyStatus = document.createElement("p");
  groupStudyStatus.textContent = "Group Study: Waiting for users...";
  document.querySelector(".upcoming-sessions")?.appendChild(groupStudyStatus);


  