document.addEventListener("DOMContentLoaded", () => {
  fetch("get_user_data.php")
    .then((response) => {
      if (!response.ok) throw new Error("Not logged in");
      return response.json();
    })
    .then((data) => {
      // Check if there is an error message in the response
      if (data.error) {
        throw new Error(data.error);
      }

      const welcomeEl = document.getElementById("welcome-msg");
      welcomeEl.textContent = `Welcome, ${data.username} 🎉`;
    })
    .catch((err) => {
      console.error(err);
      document.getElementById("welcome-msg").textContent = "Welcome, Guest!";
      // Redirect to login page if not logged in
      if (
        err.message === "Not logged in" ||
        err.message === "User not logged in"
      ) {
        alert("Please login to access your profile.");
        window.location.href = "frontend/html/page/login.html";
      }
    });
});
