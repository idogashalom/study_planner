// 📁 public/js/assignments.js

const API_BASE = '../../../server/route/assignmentRoutes.php';
const USER_ID = 1; // Replace this with the actual logged-in user's ID (possibly from session)

// Get all assignments on page load
window.addEventListener("DOMContentLoaded", () => {
  fetchAssignments();
});

function fetchAssignments() {
  fetch(`${API_BASE}?action=get&user_id=${USER_ID}`)
    .then((res) => res.json())
    .then((assignments) => renderAssignments(assignments))
    .catch((err) => console.error("Error fetching assignments:", err));
}

function renderAssignments(assignments) {
  const assignmentList = document.getElementById("assignment-list");
  assignmentList.innerHTML = "";

  if (!assignments.length) {
    assignmentList.innerHTML = "<p>No assignments added yet.</p>";
    return;
  }

  assignments.forEach((task) => {
    const div = document.createElement("div");
    div.className = "assignment-item";
    div.innerHTML = `
      <strong>${task.subject}</strong><br>
      <span>${task.description}</span><br>
      <small>Due: ${task.due_date}</small><br>
      <button class="delete-assignment" data-id="${task.id}">Delete</button>
    `;
    assignmentList.appendChild(div);
  });

  attachDeleteListeners();
}

function attachDeleteListeners() {
  document.querySelectorAll(".delete-assignment").forEach((button) => {
    button.addEventListener("click", (e) => {
      const id = e.target.getAttribute("data-id");

      fetch(`${API_BASE}?action=delete`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ id })
      })
        .then((res) => res.json())
        .then(() => fetchAssignments())
        .catch((err) => console.error("Delete failed:", err));
    });
  });
}

document
  .getElementById("open-assignment-form")
  .addEventListener("click", () => {
    const subject = document.getElementById("assignment-subject").value.trim();
    const description = document.getElementById("assignment-desc").value.trim();
    const dueDate = document.getElementById("assignment-date").value;

    if (!subject || !description || !dueDate) {
      alert("Please fill in all fields.");
      return;
    }

    assignments.push({ subject, description, dueDate });
    localStorage.setItem("assignments", JSON.stringify(assignments));

    document.getElementById("assignment-subject").value = "";
    document.getElementById("assignment-desc").value = "";
    document.getElementById("assignment-date").value = "";

    renderAssignments();
  });

window.addEventListener("DOMContentLoaded", renderAssignments);

function saveAssignment() {
  const subject = document.getElementById("subject").value.trim();
  const task = document.getElementById("assignment").value.trim();
  const dueDate = document.getElementById("due_date").value;

  if (subject && task && dueDate) {
    const assignment = {
      subject,
      task,
      dueDate,
    };

    let assignments = JSON.parse(localStorage.getItem("assignments")) || [];
    assignments.push(assignment);
    localStorage.setItem("assignments", JSON.stringify(assignments));

    // Redirect to assignments page
    window.location.href = "assignments.php";
  } else {
    alert("Please fill in all assignment fields.");
  }
}

document
  .getElementById("open-assignment-form")
  .addEventListener("click", function () {
    const subject = document.getElementById("assignment-subject").value.trim();
    const description = document.getElementById("assignment-desc").value.trim();
    const dueDate = document.getElementById("assignment-date").value;

    const wordCount = description
      .split(/\s+/)
      .filter((word) => word.length > 0).length;

    if (!subject || !description || !dueDate) {
      alert("Please fill in all fields.");
      return;
    }

    if (wordCount < 180) {
      alert(
        `Assignment must be at least 180 words. Current word count: ${wordCount}`
      );
      return;
    }

    // Submit the data (AJAX or form submission here)
    alert("Assignment submitted successfully!");
  });
