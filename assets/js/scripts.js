// assets/js/scripts.js
document.addEventListener("DOMContentLoaded", () => {
  // Smooth fade-in on page load
  document.body.style.opacity = 0;
  setTimeout(() => {
    document.body.style.transition = "opacity 0.8s ease-in-out";
    document.body.style.opacity = 1;
  }, 100);

  // Global form validation with visual feedback
  document.querySelectorAll("form").forEach(form => {
    form.addEventListener("submit", (e) => {
      let valid = true;
      const fields = form.querySelectorAll("input[required], select[required], textarea[required]");
      fields.forEach(field => {
        if (!field.value.trim()) {
          valid = false;
          field.classList.add("is-invalid");
        } else {
          field.classList.remove("is-invalid");
        }
      });
      if (!valid) {
        e.preventDefault();
        if (!form.querySelector('.alert')) {
          const alertDiv = document.createElement('div');
          alertDiv.className = 'alert alert-danger';
          alertDiv.innerText = 'Please fill out all required fields.';
          form.insertBefore(alertDiv, form.firstChild);
          setTimeout(() => { alertDiv.remove(); }, 3000);
        }
      }
    });
  });

  // Toggle department field for Register page
  const roleSelect = document.getElementById("role");
  if (roleSelect) {
    roleSelect.addEventListener("change", () => {
      const departmentField = document.getElementById("departmentField");
      if (["student", "coordinator", "instructor"].includes(roleSelect.value)) {
        departmentField.style.display = "block";
      } else {
        departmentField.style.display = "none";
      }
    });
  }
});
