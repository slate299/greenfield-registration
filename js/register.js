// register.js - Client-side validation for registration
document
  .getElementById("registerForm")
  .addEventListener("submit", function (e) {
    // Get form values
    var firstName = document.getElementById("firstName").value;
    var lastName = document.getElementById("lastName").value;
    var studentId = document.getElementById("studentId").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("pw").value;
    var password2 = document.getElementById("pw2").value;

    // Validation
    if (
      firstName === "" ||
      lastName === "" ||
      studentId === "" ||
      email === "" ||
      password === "" ||
      password2 === ""
    ) {
      showMessage("Please fill in all fields.", "error");
      e.preventDefault();
      return false;
    }

    if (password !== password2) {
      showMessage("Passwords do not match.", "error");
      e.preventDefault();
      return false;
    }

    if (password.length < 6) {
      showMessage("Password must be at least 6 characters.", "error");
      e.preventDefault();
      return false;
    }

    // If all validation passes, form submits normally to PHP
    return true;
  });

function showMessage(msg, type) {
  var messageDiv = document.getElementById("form-message");
  messageDiv.innerHTML = msg;
  messageDiv.className = "alert-message alert-" + type;
  messageDiv.style.display = "block";

  // Auto-hide after 5 seconds
  setTimeout(function () {
    messageDiv.style.display = "none";
  }, 5000);
}

// Clear message when typing
function clearMessage() {
  var msg = document.getElementById("form-message");
  if (msg) msg.style.display = "none";
}

// Attach clear message to all inputs
document.querySelectorAll("input").forEach(function (input) {
  input.addEventListener("input", clearMessage);
});
