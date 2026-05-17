// js/login.js - Teammate's JavaScript
var role = "student";

function switchRole(r) {
  role = r;
  document.getElementById("roleInput").value = r;
  document.getElementById("email").value = "";
  document.getElementById("password").value = "";

  var errorDiv = document.getElementById("error-msg");
  if (errorDiv) {
    errorDiv.style.display = "none";
  }

  if (role == "student") {
    document.getElementById("btnStudent").className = "toggle-btn active";
    document.getElementById("btnAdmin").className = "toggle-btn";
    document.getElementById("formTitle").textContent = "Student Login";
    document.getElementById("emailLabel").textContent = "Student Email:";
    document.getElementById("loginBtn").textContent = "Login as Student";
    document.getElementById("registerLink").style.display = "block";
    document.getElementById("adminNote").style.display = "none";
  } else {
    document.getElementById("btnAdmin").className = "toggle-btn active";
    document.getElementById("btnStudent").className = "toggle-btn";
    document.getElementById("formTitle").textContent = "Admin Login";
    document.getElementById("emailLabel").textContent = "Admin Email:";
    document.getElementById("loginBtn").textContent = "Login as Admin";
    document.getElementById("registerLink").style.display = "none";
    document.getElementById("adminNote").style.display = "block";
  }
}

// Clear error message when typing
document.getElementById("email")?.addEventListener("input", function () {
  var err = document.getElementById("error-msg");
  if (err) err.style.display = "none";
});

document.getElementById("password")?.addEventListener("input", function () {
  var err = document.getElementById("error-msg");
  if (err) err.style.display = "none";
});
