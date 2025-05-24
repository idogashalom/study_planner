//const menuBtn = document.getElementById("menuToggle");
//const sidebar = document.getElementById("mobileSidebar");

//menuBtn.addEventListener("click", () => {
// sidebar.style.display = sidebar.style.display === "flex" ? "none" : "flex";
//});

document.getElementById("menuToggle").addEventListener("click", function () {
  document.getElementById("navLinks").classList.toggle("show");
});

//  const isLoggedIn = localStorage.getItem("isLoggedIn");
// if (!isLoggedIn || isLoggedIn !== "true") {
//   alert("Access denied. Please log in to access page.");
//     window.location.href = "frontend\html\page\login.html";
// }
