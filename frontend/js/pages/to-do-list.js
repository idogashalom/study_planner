document.addEventListener("DOMContentLoaded", () => {
  const taskInput = document.getElementById("todo-input");
  const addTaskBtn = document.getElementById("add-todo");
  const taskList = document.getElementById("task-list");

  // Load tasks from localStorage on page load
  let tasks = JSON.parse(localStorage.getItem("tasks")) || [];
  renderTasks();

  addTaskBtn.addEventListener("click", () => {
    const taskText = taskInput.value.trim();
    if (taskText !== "") {
      tasks.push(taskText);
      localStorage.setItem("tasks", JSON.stringify(tasks));
      renderTasks();
      taskInput.value = "";
    }
  });

  // Remove task when clicked
  taskList.addEventListener("click", (e) => {
    if (e.target.classList.contains("remove-task")) {
      const index = e.target.dataset.index;
      tasks.splice(index, 1);
      localStorage.setItem("tasks", JSON.stringify(tasks));
      renderTasks();
    }
  });

  function renderTasks() {
    taskList.innerHTML = "";
    tasks.forEach((task, index) => {
      const taskItem = document.createElement("div");
      taskItem.className = "task-item";
      taskItem.innerHTML = `
        <span>${task}</span>
        <button class="remove-task" data-index="${index}">✖</button>
      `;
      taskList.appendChild(taskItem);
    });
  }
});
