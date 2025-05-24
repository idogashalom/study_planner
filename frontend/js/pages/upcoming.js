document.addEventListener("DOMContentLoaded", () => {
    const openFormBtn = document.getElementById("open-session-form");
    const form = document.getElementById("addSessionForm");
    const upcomingContainer = document.getElementById("upcoming-sessions");
  
    const alarmSound = new Audio("sounds/alarm.mp3");
  
    let sessions = JSON.parse(localStorage.getItem("studySessions")) || [];
  
    renderSessions();
    setInterval(checkAlarmsAndStatus, 30000); // Check every 30 seconds
  
    openFormBtn.addEventListener("click", () => {
      const title = form.title.value.trim();
      const subject = form.subject.value.trim();
      const start = form.start_time.value;
      const end = form.end_time.value;
  
      if (title && subject && start && end) {
        const today = new Date();
        const dateString = today.toISOString().split("T")[0]; // YYYY-MM-DD
  
        const session = {
          title,
          subject,
          start,
          end,
          date: dateString,
          status: "Planned",
          notified: false
        };
  
        sessions.push(session);
        localStorage.setItem("studySessions", JSON.stringify(sessions));
        renderSessions();
        form.reset();
      } else {
        alert("Please fill in all session details.");
      }
    });
  
    function renderSessions() {
      upcomingContainer.innerHTML = "";
      const now = new Date();
  
      if (sessions.length === 0) {
        upcomingContainer.innerHTML = "<p>No sessions added yet.</p>";
        return;
      }
  
      sessions.forEach((session, index) => {
        const endDateTime = new Date(`${session.date}T${session.end}`);
        const status = endDateTime < now ? "Completed" : "Planned";
  
        sessions[index].status = status;
  
        const div = document.createElement("div");
        div.className = "session-box";
        div.innerHTML = `
          <h3>${session.title} - ${session.subject}</h3>
          <p>${formatDate(session.date)}</p>
          <p>${session.start} - ${session.end}</p>
          <span class="status ${status.toLowerCase()}">${status}</span>
          ${status === "Completed" ? `<button class="delete-session" data-index="${index}">Delete</button>` : ""}
        `;
  
        upcomingContainer.appendChild(div);
      });
  
      attachDeleteListeners();
      localStorage.setItem("studySessions", JSON.stringify(sessions));
    }
  
    function attachDeleteListeners() {
      document.querySelectorAll(".delete-session").forEach(button => {
        button.addEventListener("click", () => {
          const index = button.getAttribute("data-index");
          sessions.splice(index, 1);
          localStorage.setItem("studySessions", JSON.stringify(sessions));
          renderSessions();
        });
      });
    }
  
    function checkAlarmsAndStatus() {
      const now = new Date();
      sessions.forEach((session, index) => {
        const startTime = new Date(`${session.date}T${session.start}`);
        const endTime = new Date(`${session.date}T${session.end}`);
  
        // Update status
        if (endTime < now && session.status !== "Completed") {
          sessions[index].status = "Completed";
        }
  
        // Alarm
        if (
          startTime - now <= 60000 &&
          startTime - now > 0 &&
          !session.notified
        ) {
          alarmSound.play();
          alert(`Reminder: "${session.title}" starts in 1 minute!`);
          sessions[index].notified = true;
        }
      });
  
      localStorage.setItem("studySessions", JSON.stringify(sessions));
      renderSessions();
    }
  
    function formatDate(dateStr) {
      const date = new Date(dateStr);
      return date.toLocaleDateString("en-US", {
        month: "long",
        day: "numeric",
        year: "numeric"
      });
    }
  });
   