const subjectChart = new Chart(document.getElementById("subjectChart"), {
  type: "doughnut",
  data: {
    labels: [],
    datasets: [{
      label: "Study Time (hrs)",
      data: [],
      backgroundColor: ["#3498db", "#2ecc71", "#f1c40f", "#e67e22", "#9b59b6"],
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: "right" },
      title: { display: true, text: "Study Time by Subject" }
    }
  }
});

const trendChart = new Chart(document.getElementById("trendChart"), {
  type: "line",
  data: {
    labels: [],
    datasets: [{
      label: "Study Hours",
      data: [],
      fill: false,
      borderColor: "#2ecc71",
      tension: 0.1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: "top" },
      title: { display: true, text: "Study Trends" }
    }
  }
});

function updateCharts(range = 'week') {
  fetch(`../../../server/route/analyticsRoutes.php?action=analytics&range=${range}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const subjects = data.data.map(item => item.subject);
        const minutes = data.data.map(item => item.total_minutes);
        const hours = minutes.map(m => Math.round((m / 60) * 10) / 10);
        const pomodoros = data.data.map(item => item.total_pomodoros);

        // Update Doughnut Chart
        subjectChart.data.labels = subjects;
        subjectChart.data.datasets[0].data = hours;
        subjectChart.update();

        // Update Trend Chart
        trendChart.data.labels = subjects;
        trendChart.data.datasets[0].data = hours;
        trendChart.update();

        // Update summary cards
        const { total_sessions, total_minutes, total_pomodoros } = data.summary;
        document.getElementById("totalStudyTime").innerText = `${Math.floor(total_minutes / 60)}h ${total_minutes % 60}m`;
        document.getElementById("totalSessions").innerText = total_sessions;
        document.getElementById("totalPomodoros").innerText = total_pomodoros;
        document.getElementById("productivityScore").innerText = Math.round((total_pomodoros / total_sessions) * 100) + '%';

        document.getElementById("emptyState").style.display = "none";
      } else {
        document.getElementById("emptyState").style.display = "block";
      }
    })
    .catch(err => {
      console.error("Failed to fetch analytics data", err);
    });
}

// Trigger update on load
document.addEventListener("DOMContentLoaded", () => {
  updateCharts();

  // Add event listeners to time range buttons
  document.querySelectorAll(".time-range-button").forEach(btn => {
    btn.addEventListener("click", () => {
      const range = btn.dataset.range;
      updateCharts(range);
    });
  });
});
