// admin_dashboard.js - Admin panel interactions

// Set active sidebar link based on current page
document.addEventListener("DOMContentLoaded", function () {
  const currentPage = window.location.pathname.split("/").pop();
  const links = document.querySelectorAll(".sidebar a");

  links.forEach((link) => {
    const href = link.getAttribute("href");
    if (href === currentPage) {
      link.classList.add("active");
    }
  });
});

// Auto-hide alert messages after 5 seconds
setTimeout(function () {
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    alert.style.transition = "opacity 0.5s";
    alert.style.opacity = "0";
    setTimeout(() => {
      if (alert.parentNode) alert.remove();
    }, 500);
  });
}, 5000);

// Confirm delete function
function confirmDelete(courseId) {
  return confirm(
    "⚠️ Are you sure you want to delete this course?\n\nThis action cannot be undone.",
  );
}
