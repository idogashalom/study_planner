function validateAndRedirect() {
    const name = document.getElementById("username").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

  
    if (name !== "" && email !== "" && password !== "") {
      // Store data in localStorage
      localStorage.setItem("username", name);
      localStorage.setItem("email", email);
      localStorage.setItem("password", password);
  
      // Redirect to session page
      window.location.href = "/server/controller/pages/register.php";
    } else {
      alert("Please fill in all required fields.");
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    const username = localStorage.getItem("username");
    if (username) {
      document.getElementById("welcome-msg").textContent = `Welcome, ${username} 🎓`;
    }
  });
  