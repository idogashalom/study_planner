// Pie Chart for Tasks
const taskChart = new Chart(document.getElementById("taskChart"), {
    type: "doughnut",
    data: {
      labels: ["Completed", "Incompleted"],
      datasets: [{
        label: "Tasks",
        data: [80, 20],
        backgroundColor: ["#0b318f", "#a7d3db"],
        borderWidth: 0,
      }],
    },
    options: {
      cutout: "60%",
      plugins: {
        legend: { display: false },
      },
    },
  });
  
  // Bar Chart for Leadership Board
  const barChart = new Chart(document.getElementById("barChart"), {
    type: "bar",
    data: {
      labels: ["U1", "U2", "U3", "U4", "U5", "U6", "U7", "U8"],
      datasets: [{
        label: "Points",
        data: [5, 10, 15, 20, 25, 30, 35, 40],
        backgroundColor: "red",
        borderRadius: 4,
      }],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
      plugins: {
        legend: { display: false },
      },
    },
  });
  
  // Dark Mode Toggle
  function toggleDarkMode() {
    document.body.classList.toggle("dark");
    document.body.classList.toggle("light");
  }
  

  
  fetch('/server/controller/pages/session-data.php')
  .then(res => res.json())
  .then(data => {
    const ctx = document.getElementById("taskChart").getContext("2d");
    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Completed", "Incomplete"],
        datasets: [{
          data: [data.completed, data.incomplete],
          backgroundColor: ["#4CAF50", "#f44336"]
        }]
      }
    });

    const barCtx = document.getElementById("barChart").getContext("2d");
    new Chart(barCtx, {
      type: "bar",
      data: {
        labels: data.leaders.map(l => l.name),
        datasets: [{
          label: "Points",
          data: data.leaders.map(l => l.points),
          backgroundColor: "#2196F3"
        }]
      }
    });
  });
