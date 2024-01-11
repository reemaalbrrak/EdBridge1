function filterAnnouncements() {
  var courseSelect = document.getElementById("course-select");
  var selectedCourse = courseSelect.value;
  var table = document.getElementById("announcement-table");
  var rows = table.getElementsByTagName("tr");

  for (var i = 0; i < rows.length; i++) {
    var course = rows[i].getElementsByTagName("td")[0];

    if (selectedCourse === "all" || course.innerHTML === selectedCourse) {
      rows[i].style.display = "";
    } else {
      rows[i].style.display = "none";
    }
  }
}

window.addEventListener("DOMContentLoaded", function() {
  var saveButton = document.querySelector("button[type='submit']");
  saveButton.addEventListener("click", function(event) {
    event.preventDefault();
    var notificationType = document.getElementById("notification-type").value;
    var frequency = document.getElementById("frequency").value;
    console.log("Selected notification type: " + notificationType);
    console.log("Selected notification frequency: " + frequency);
    // Perform save operation or send data to server
  });
});
