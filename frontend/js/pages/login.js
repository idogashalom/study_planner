// function validateAndRedirect() {
//   const name = document.getElementById("username").value.trim();
//   const email = document.getElementById("password").value.trim();

//   if (name !== "" && password !== "") {
//     // Store data in localStorage
//     localStorage.setItem("username", name);
//     localStorage.setItem("password", password);

//     // Redirect to session page
//     window.location.href = "server\controller\pages\login.php";
//   } else {
//     alert("Please fill in all required fields.");
//   }
// }

// document.addEventListener("DOMContentLoaded", () => {
//   const username = localStorage.getItem("username");
//   if (username) {
//     document.getElementById("welcome-msg").textContent = `Welcome, ${username} 🎓`;
//   }
// });
