// my_courses.js - Interactive features for My Courses page

let selectedCourseId = null;
let selectedCourseName = "";

// Filter courses by status
function filterCourse(status) {
  let cards = document.querySelectorAll(".card");

  // Update active button styling
  document.querySelectorAll(".buttons button").forEach((btn) => {
    btn.classList.remove("active-filter");
  });
  event.target.classList.add("active-filter");

  cards.forEach(function (card) {
    if (status === "all" || card.dataset.status === status) {
      card.style.display = "block";
    } else {
      card.style.display = "none";
    }
  });
}

// Search courses
function searchCourse(value) {
  let text = value.toLowerCase();
  let cards = document.querySelectorAll(".card");

  cards.forEach(function (card) {
    let name = card.dataset.name;
    if (name.includes(text)) {
      card.style.display = "block";
    } else {
      card.style.display = "none";
    }
  });
}

// Open drop confirmation modal
function openModal(courseId, courseName) {
  selectedCourseId = courseId;
  selectedCourseName = courseName;
  document.getElementById("courseNameDisplay").innerHTML =
    "Are you sure you want to drop <strong>" + courseName + "</strong>?";
  document.getElementById("modal").style.display = "flex";
}

// Close modal
function closeModal() {
  document.getElementById("modal").style.display = "none";
  selectedCourseId = null;
  selectedCourseName = "";
}

// Drop course - redirect to PHP drop handler
function dropCourse() {
  if (selectedCourseId) {
    window.location.href =
      "drop_course.php?course_id=" + selectedCourseId + "&from=my_courses";
  }
  closeModal();
}

// View course details
function viewCourse(courseName) {
  alert(
    "📚 Course Details\n\n" +
      courseName +
      "\n\nMore details will be available soon.",
  );
}

// Set active sidebar link
document.addEventListener("DOMContentLoaded", function () {
  // Set active class on sidebar
  const links = document.querySelectorAll(".sidebar a");
  links.forEach((link) => {
    if (link.getAttribute("href") === "my_courses.php") {
      link.classList.add("active");
    }
  });
});
