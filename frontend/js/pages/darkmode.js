const toggleDark = document.getElementById("toggleDark");
toggleDark.addEventListener("click", () => {
  document.body.classList.toggle("dark");
  localStorage.setItem("mode", document.body.classList.contains("dark") ? "dark" : "light");
});
window.addEventListener("load", () => {
  if (localStorage.getItem("mode") === "dark") {
    document.body.classList.add("dark");
  }
});
