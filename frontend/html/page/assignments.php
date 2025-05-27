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
  <title>Assignments - StudyMake</title>
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

    .assignments-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .assignments-title {
      font-size: 1.75rem;
      font-weight: 600;
    }

    .assignments-container {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1.5rem;
    }

    .assignments-card {
      background-color: var(--card-bg);
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      border: 1px solid var(--border);
    }

    .assignment-form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 1.5rem;
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

    .add-btn {
      grid-column: span 2;
      background-color: var(--primary);
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
    }

    .add-btn:hover {
      background-color: var(--primary-dark);
    }

    .assignment-filters {
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

    .assignments-list {
      display: grid;
      gap: 1rem;
    }

    .assignment-item {
      display: grid;
      grid-template-columns: auto 1fr auto auto;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      border-radius: 8px;
      background-color: var(--bg);
      border: 1px solid var(--border);
    }

    .assignment-checkbox {
      width: 1.25rem;
      height: 1.25rem;
      accent-color: var(--primary);
      cursor: pointer;
    }

    .assignment-details {
      display: flex;
      flex-direction: column;
    }

    .assignment-title {
      font-weight: 500;
      margin-bottom: 0.25rem;
    }

    .assignment-title.completed {
      text-decoration: line-through;
      color: var(--text-light);
    }

    .assignment-meta {
      display: flex;
      gap: 1rem;
      font-size: 0.875rem;
      color: var(--text-light);
    }

    .assignment-due {
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .assignment-priority {
      padding: 0.25rem 0.5rem;
      border-radius: 12px;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .priority-high {
      background-color: rgba(239, 68, 68, 0.1);
      color: var(--error);
    }

    .priority-medium {
      background-color: rgba(245, 158, 11, 0.1);
      color: var(--warning);
    }

    .priority-low {
      background-color: rgba(16, 185, 129, 0.1);
      color: var(--success);
    }

    .assignment-actions {
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

    .action-btn.edit-btn:hover {
      color: var(--warning);
    }

    .action-btn.delete-btn:hover {
      color: var(--error);
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

      .assignment-form {
        grid-template-columns: 1fr;
      }

      .add-btn {
        grid-column: span 1;
      }

      .assignment-item {
        grid-template-columns: auto 1fr;
        grid-template-rows: auto auto;
      }

      .assignment-actions {
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
    <div class="assignments-header">
      <h1 class="assignments-title">Assignments</h1>
      <div class="user-greeting">
        <?php echo htmlspecialchars($_SESSION['name']); ?>'s Work
      </div>
    </div>

    <div class="assignments-container">
      <div class="assignments-card">
        <!-- Add New Assignment Form -->
        <form id="addAssignmentForm" class="assignment-form">
          <div class="form-group">
            <label for="assignmentTitle">Title</label>
            <input
              type="text"
              id="assignmentTitle"
              class="form-control"
              placeholder="Assignment title"
              required>
          </div>

          <div class="form-group">
            <label for="assignmentSubject">Subject</label>
            <input
              type="text"
              id="assignmentSubject"
              class="form-control"
              placeholder="Subject/Course">
          </div>

          <div class="form-group">
            <label for="assignmentDue">Due Date</label>
            <input
              type="date"
              id="assignmentDue"
              class="form-control"
              required>
          </div>

          <div class="form-group">
            <label for="assignmentPriority">Priority</label>
            <select id="assignmentPriority" class="form-control">
              <option value="low">Low</option>
              <option value="medium" selected>Medium</option>
              <option value="high">High</option>
            </select>
          </div>

          <button type="submit" class="add-btn">
            <i class='bx bx-plus'></i> Add Assignment
          </button>
        </form>

        <!-- Assignment Filters -->
        <div class="assignment-filters">
          <button class="filter-btn active" data-filter="all">All</button>
          <button class="filter-btn" data-filter="pending">Pending</button>
          <button class="filter-btn" data-filter="completed">Completed</button>
          <button class="filter-btn" data-filter="overdue">Overdue</button>
        </div>

        <!-- Assignments List -->
        <div class="assignments-list" id="assignmentsList">
          <!-- Sample Assignment Items (will be populated dynamically) -->
          <div class="assignment-item">
            <input type="checkbox" class="assignment-checkbox">
            <div class="assignment-details">
              <span class="assignment-title">Math Problem Set #5</span>
              <div class="assignment-meta">
                <span>Algebra 101</span>
                <span class="assignment-due">
                  <i class='bx bx-calendar'></i> Due: Tomorrow
                </span>
              </div>
            </div>
            <span class="assignment-priority priority-medium">Medium</span>
            <div class="assignment-actions">
              <button class="action-btn edit-btn"><i class='bx bx-edit'></i></button>
              <button class="action-btn delete-btn"><i class='bx bx-trash'></i></button>
            </div>
          </div>

          <div class="assignment-item">
            <input type="checkbox" class="assignment-checkbox" checked>
            <div class="assignment-details">
              <span class="assignment-title completed">History Essay</span>
              <div class="assignment-meta">
                <span>World History</span>
                <span class="assignment-due">
                  <i class='bx bx-calendar'></i> Submitted: Today
                </span>
              </div>
            </div>
            <span class="assignment-priority priority-high">High</span>
            <div class="assignment-actions">
              <button class="action-btn edit-btn"><i class='bx bx-edit'></i></button>
              <button class="action-btn delete-btn"><i class='bx bx-trash'></i></button>
            </div>
          </div>

          <div class="empty-state" id="emptyState" style="display: none;">
            <i class='bx bx-book-open' style="font-size: 2rem; margin-bottom: 1rem;"></i>
            <p>No assignments found. Add one to get started!</p>
          </div>
        </div>
      </div>
    </div>
  </div>


  <script src="../../js/pages/sidebar.js"></script>
  <script>
    // Wait until the entire HTML page is fully loaded before running this code
    document.addEventListener('DOMContentLoaded', () => {

      // Select and store references to important DOM elements
      const assignmentForm = document.getElementById('addAssignmentForm'); // The form used to add new assignments
      const assignmentsList = document.getElementById('assignmentsList'); // Where assignments will be displayed
      const emptyState = document.getElementById('emptyState'); // Message shown when no assignments exist
      const filterBtns = document.querySelectorAll('.filter-btn'); // Buttons to filter assignments (not fully used here)

      // Function to load assignments from the backend server
      function fetchAssignments() {
        // Send a GET request to our backend route to get all assignments
        fetch('../../../server/route/assignmentRoutes.php?action=get')
          .then(res => res.json()) // Parse the response as JSON data
          .then(data => {
            // After data is received, show it on the page
            renderAssignments(data);
          })
          .catch(console.error); // Show error in the browser console if something fails
      }

      // Function to display (render) assignments in the page
      function renderAssignments(assignments) {
        assignmentsList.innerHTML = ''; // Clear all existing assignment HTML inside the list

        if (assignments.length === 0) {
          // If there are no assignments, show the empty message
          emptyState.style.display = 'block';
          return;
        }

        // Hide the empty message since we now have assignments
        emptyState.style.display = 'none';

        // Loop through each assignment and create a visual element for it
        assignments.forEach(assignment => {
          const assignmentEl = createAssignmentElement(assignment);
          assignmentsList.appendChild(assignmentEl); // Add the new element to the list
        });
      }

      // Function to create HTML structure for one assignment
      function createAssignmentElement(assignment) {
        const div = document.createElement('div'); // Create a new div
        div.className = 'assignment-item'; // Add class for styling
        div.dataset.id = assignment.id; // Save the assignment ID in the element

        // Prepare due date display logic
        const dueDateObj = new Date(assignment.due_date); // Convert due date string to Date object
        const today = new Date(); // Get today's date
        const tomorrow = new Date(); // Create another Date object for tomorrow
        tomorrow.setDate(today.getDate() + 1); // Increase day by 1 for tomorrow

        let dueText = ''; // This will store the display text for the due date

        // Compare due date to today and tomorrow
        if (dueDateObj.toDateString() === today.toDateString()) {
          dueText = 'Due: Today';
        } else if (dueDateObj.toDateString() === tomorrow.toDateString()) {
          dueText = 'Due: Tomorrow';
        } else {
          // Format date as something like "May 25"
          dueText = `Due: ${dueDateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}`;
        }

        // Handle priority text and class (e.g., low, medium, high)
        const priorityClass = `priority-${assignment.priority}`;
        const priorityText = assignment.priority.charAt(0).toUpperCase() + assignment.priority.slice(1); // capitalize first letter

        // Create the HTML inside the assignment box using template strings
        div.innerHTML = `
        <input type="checkbox" class="assignment-checkbox" ${assignment.completed ? 'checked' : ''}>
        <div class="assignment-details">
          <span class="assignment-title ${assignment.completed ? 'completed' : ''}">${assignment.title}</span>
          <div class="assignment-meta">
            <span>${assignment.subject || 'No subject'}</span>
            <span class="assignment-due"><i class='bx bx-calendar'></i> ${assignment.completed ? 'Submitted' : dueText}</span>
          </div>
        </div>
        <span class="assignment-priority ${priorityClass}">${priorityText}</span>
        <div class="assignment-actions">
          <button class="action-btn edit-btn"><i class='bx bx-edit'></i></button>
          <button class="action-btn delete-btn"><i class='bx bx-trash'></i></button>
        </div>
      `;

        return div; // Return the created assignment element
      }

      // Handle when the assignment form is submitted
      assignmentForm.addEventListener('submit', (e) => {
        e.preventDefault(); // Stop the form from refreshing the page

        // Get values from input fields
        const title = document.getElementById('assignmentTitle').value.trim();
        const subject = document.getElementById('assignmentSubject').value.trim();
        const due_date = document.getElementById('assignmentDue').value;
        const priority = document.getElementById('assignmentPriority').value;

        // Do not continue if title or due date is missing
        if (!title || !due_date) return;

        // Send the new assignment to the backend using a POST request
        fetch('../../../server/route/assignmentRoutes.php?action=add', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json' // We're sending JSON data
            },
            body: JSON.stringify({
              title,
              subject,
              due_date,
              priority,
              action: 'add'
            })
          })
          .then(res => res.json())
          .then(response => {
            if (response.success) {
              fetchAssignments(); // Reload the assignment list
              assignmentForm.reset(); // Clear the form inputs
            }
          })
          .catch(console.error);
      });

      // Handle clicks on assignments (edit, delete, checkbox toggle)
      assignmentsList.addEventListener('click', (e) => {
        const target = e.target; // What the user clicked on
        const assignmentEl = target.closest('.assignment-item'); // Find the nearest parent assignment element
        if (!assignmentEl) return;

        const id = assignmentEl.dataset.id; // Get the assignment ID from the element

        // User clicked on the checkbox to mark as complete/incomplete
        if (target.classList.contains('assignment-checkbox')) {
          const completed = target.checked ? 1 : 0; // Convert checkbox state to 1 or 0

          fetch('../../../server/route/assignmentRoutes.php?action=toggle', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                id,
                completed,
                action: 'toggle'
              })
            })
            .then(res => res.json())
            .then(response => {
              if (response.success) {
                const titleEl = assignmentEl.querySelector('.assignment-title');
                const dueEl = assignmentEl.querySelector('.assignment-due');
                if (completed) {
                  titleEl.classList.add('completed'); // Add strikethrough style
                  dueEl.innerHTML = `<i class='bx bx-calendar'></i> Submitted`; // Change due text
                } else {
                  titleEl.classList.remove('completed'); // Remove strikethrough
                  // You may optionally recalculate the due text again
                }
              }
            })
            .catch(console.error);
        }

        // User clicked the delete button
        if (target.closest('.delete-btn')) {
          fetch('../../../server/route/assignmentRoutes.php?action=delete', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                id,
                action: 'delete'
              })
            })
            .then(res => res.json())
            .then(response => {
              if (response.success) {
                fetchAssignments(); // Refresh the list after deletion
              }
            })
            .catch(console.error);
        }

        // User clicked the edit button
        if (target.closest('.edit-btn')) {
          const currentTitle = assignmentEl.querySelector('.assignment-title').textContent;
          const currentSubject = assignmentEl.querySelector('.assignment-meta > span:first-child').textContent;

          const newTitle = prompt('Edit assignment title:', currentTitle); // Ask user to enter new title
          const newSubject = prompt('Edit subject:', currentSubject); // Ask user to enter new subject

          if (newTitle && newTitle.trim() !== '') {
            fetch('../../../server/route/assignmentRoutes.php?action=update', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                  id,
                  title: newTitle.trim(),
                  subject: newSubject ? newSubject.trim() : '',
                  due_date: assignmentEl.querySelector('.assignment-due').textContent, // For demo purposes
                  priority: 'medium', // Set as default, but can be dynamic
                  completed: assignmentEl.querySelector('.assignment-checkbox').checked ? 1 : 0,
                  action: 'update'
                })
              })
              .then(res => res.json())
              .then(response => {
                if (response.success) {
                  fetchAssignments(); // Refresh with updated assignment
                }
              })
              .catch(console.error);
          }
        }
      });

      // Call the function once when page loads to fetch and display assignments
      fetchAssignments();
    });
  </script>

</body>

</html>