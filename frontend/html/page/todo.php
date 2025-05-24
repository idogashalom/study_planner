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
  <title>To-Do List - StudyMake</title>
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

    .todo-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .todo-title {
      font-size: 1.75rem;
      font-weight: 600;
    }

    .todo-container {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1.5rem;
    }

    .todo-card {
      background-color: var(--card-bg);
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      border: 1px solid var(--border);
    }

    .todo-form {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .todo-input {
      flex: 1;
      padding: 0.875rem;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 1rem;
      background-color: var(--bg);
      color: var(--text);
      transition: all 0.2s ease;
    }

    .todo-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .add-btn {
      background-color: var(--primary);
      color: white;
      padding: 0 1.5rem;
      border: none;
      border-radius: 8px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .add-btn:hover {
      background-color: var(--primary-dark);
    }

    .todo-list {
      list-style: none;
    }

    .todo-item {
      display: flex;
      align-items: center;
      padding: 1rem 0;
      border-bottom: 1px solid var(--border);
    }

    .todo-item:last-child {
      border-bottom: none;
    }

    .todo-checkbox {
      margin-right: 1rem;
      width: 1.25rem;
      height: 1.25rem;
      accent-color: var(--primary);
      cursor: pointer;
    }

    .todo-text {
      flex: 1;
      font-size: 1rem;
    }

    .todo-text.completed {
      text-decoration: line-through;
      color: var(--text-light);
    }

    .todo-date {
      font-size: 0.875rem;
      color: var(--text-light);
      margin-right: 1rem;
    }

    .todo-actions {
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

    .todo-filters {
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

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 1.5rem;
      }

      .todo-form {
        flex-direction: column;
      }

      .add-btn {
        padding: 0.875rem;
      }
    }
  </style>
</head>

<body>
  <!-- Include sidebar -->
  <?php include 'sidebar.php'; ?>

  <div class="main-content">
    <div class="todo-header">
      <h1 class="todo-title">To-Do List</h1>
      <div class="user-greeting">
        <?php echo htmlspecialchars($_SESSION['name']); ?>'s Tasks
      </div>
    </div>

    <div class="todo-container">
      <div class="todo-card">
        <!-- Add New Todo Form -->
        <form id="addTodoForm" class="todo-form">
          <input
            type="text"
            name="task"
            id="todoInput"
            class="todo-input"
            placeholder="What needs to be done?"
            required>
          <button type="submit" class="add-btn">
            <i class='bx bx-plus'></i> Add
          </button>
        </form>


        <!-- Todo Filters -->
        <div class="todo-filters">
          <button class="filter-btn active" data-filter="all">All</button>
          <button class="filter-btn" data-filter="active">Active</button>
          <button class="filter-btn" data-filter="completed">Completed</button>
        </div>

        <!-- Todo List -->
        <ul class="todo-list" id="todoList">
          <!-- Sample Todo Items (will be populated dynamically) -->
          <li class="todo-item">
            <input type="checkbox" class="todo-checkbox" checked>
            <span class="todo-text completed">Complete math assignment</span>
            <span class="todo-date">Due: Tomorrow</span>
            <div class="todo-actions">
              <button class="action-btn edit-btn"><i class='bx bx-edit'></i></button>
              <button class="action-btn delete-btn"><i class='bx bx-trash'></i></button>
            </div>
          </li>
          <li class="todo-item">
            <input type="checkbox" class="todo-checkbox">
            <span class="todo-text">Read chapter 5 of biology</span>
            <span class="todo-date">Due: Friday</span>
            <div class="todo-actions">
              <button class="action-btn edit-btn"><i class='bx bx-edit'></i></button>
              <button class="action-btn delete-btn"><i class='bx bx-trash'></i></button>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <script src="../../js/pages/sidebar.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const todoForm = document.getElementById('addTodoForm');
  const todoInput = document.getElementById('todoInput');
  const todoList = document.getElementById('todoList');
  const filterBtns = document.querySelectorAll('.filter-btn');

  // Fetch and render todos from backend on load
  fetchTodos();

  todoForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const todoText = todoInput.value.trim();

    if (todoText) {
      fetch('../../../server/route/todoRoutes.php?action=add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ task: todoText })
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          fetchTodos(); // Refresh list
          todoInput.value = '';
        }
      });
    }
  });

  filterBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      filterBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      filterTodos(this.dataset.filter);
    });
  });

  todoList.addEventListener('click', function (e) {
    const target = e.target;
    const todoItem = target.closest('.todo-item');
    const todoId = todoItem?.dataset.id;

    if (!todoItem || !todoId) return;

    // Toggle completed
    if (target.classList.contains('todo-checkbox')) {
      const completed = target.checked;
      fetch(`../../../server/route/todoRoutes.php?action=toggle`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: todoId, completed })
      }).then(() => {
        todoItem.querySelector('.todo-text').classList.toggle('completed', completed);
      });
    }

    // Delete task
    if (target.classList.contains('delete-btn') || target.closest('.delete-btn')) {
      fetch(`../../../server/route/todoRoutes.php?action=delete&id=${todoId}`)
        .then(res => res.json())
        .then(res => {
          if (res.success) {
            todoItem.remove();
          }
        });
    }

    // Edit task
    if (target.classList.contains('edit-btn') || target.closest('.edit-btn')) {
      const textSpan = todoItem.querySelector('.todo-text');
      const newText = prompt('Edit your task:', textSpan.textContent);
      if (newText && newText.trim() !== '') {
        fetch(`../../../server/route/todoRoutes.php?action=update`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: todoId, task: newText.trim() })
        }).then(res => res.json())
          .then(res => {
            if (res.success) {
              textSpan.textContent = newText.trim();
            }
          });
      }
    }
  });

  function fetchTodos() {
    fetch('../../../server/route/todoRoutes.php?action=get')
      .then(res => res.json())
      .then(todos => {
        todoList.innerHTML = '';
        todos.forEach(todo => renderTodo(todo));
      });
  }

  function renderTodo(todo) {
    const li = document.createElement('li');
    li.className = 'todo-item';
    li.dataset.id = todo.id;
    li.innerHTML = `
      <input type="checkbox" class="todo-checkbox" ${todo.completed == 1 ? 'checked' : ''}>
      <span class="todo-text ${todo.completed == 1 ? 'completed' : ''}">${todo.task}</span>
      <span class="todo-date">${new Date(todo.created_at).toLocaleDateString()}</span>
      <div class="todo-actions">
        <button class="action-btn edit-btn"><i class='bx bx-edit'></i></button>
        <button class="action-btn delete-btn"><i class='bx bx-trash'></i></button>
      </div>
    `;
    todoList.appendChild(li);
  }

  function filterTodos(filter) {
    const items = todoList.querySelectorAll('.todo-item');

    items.forEach(item => {
      const isCompleted = item.querySelector('.todo-checkbox').checked;

      switch (filter) {
        case 'all':
          item.style.display = 'flex';
          break;
        case 'active':
          item.style.display = isCompleted ? 'none' : 'flex';
          break;
        case 'completed':
          item.style.display = isCompleted ? 'flex' : 'none';
          break;
      }
    });
  }
});
</script>


</body>

</html>